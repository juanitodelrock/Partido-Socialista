<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Topics.php';
require_once __DIR__ . '/../lib/Authorization.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Chart.php';


/**
 * Clase HomeController
 *
 * Controlador que maneja las acciones relacionadas con el usuario, como login, logout y registro.
 */
class HomeController
{
    private $userModel;
    private $topicModel;
    private $reportModel;
    private $chartModel;

    /**
     * Constructor de la clase HomeController
     *
     * Instancia el modelo de usuario para poder utilizarlo en las funciones de la clase.
     */
    public function __construct()
    {
        $this->userModel = new User(Database::getConnection());
        $this->topicModel = new Topic(Database::getConnection());
        $this->reportModel = new Report(Database::getConnection());
        $this->chartModel = new Chart(Database::getConnection());
    }

    /**
     * Función para validar si el usuario tiene sesión iniciada.
     *
     * Si el usuario no tiene sesión iniciada, redirecciona a la página de login.
     */
    private function require_login()
    {

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . generate_link('login'));
            exit();
        }
    }

    /**
     * Función login
     *
     * Verifica si se ha recibido un formulario de login, valida el correo y contraseña ingresados
     * y si son correctos, inicia sesión y redirige al dashboard. Si no son correctos, muestra un mensaje
     * de error en la vista de login.
     */
    public function login()
    {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->validate_user($email, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                $go2 = generate_link('dashboard');
                header('Location:' . $go2); // Redireccionar al dashboard sin mencionar index.php
            } else {
                $errorMessage = 'Correo electrónico o contraseña incorrectos';
            }
        }

        require_once 'views/login.php'; // Cargar la vista de login sin redireccionar
    }

    /**
     * Función logout
     *
     * Cierra la sesión del usuario y redirige al login.
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . generate_link('login')); // Redireccionar al login
    }

    /**
     * Función register
     *
     * Verifica si se ha recibido un formulario de registro, valida si el correo ingresado ya está registrado,
     * y si no lo está, crea un nuevo usuario en la base de datos y redirige al login. Si el correo ya está
     * registrado, muestra un mensaje de error en la vista de registro.
     */
    public function register()
    {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $userExists = $this->userModel->check_user_exists($email);

            if (!$userExists) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                $this->userModel->create_user($name, $email, $passwordHash);
                header('Location: ' . generate_link('login')); // Redireccionar al login
            } else {
                $errorMessage = 'El correo electrónico ya está registrado';
            }
        }

        require_once 'views/register.php'; // Cargar la vista de registro
    }

    /**
     * Función dashboard
     *
     * Verifica si el usuario es super administrador o no, y muestra los tópicos correspondientes.
     */
    public function dashboard()
    {
        $this->require_login();
        $db = new Database();
        $authorization = new Authorization($db->getConnection());
        $user_id = $_SESSION['user_id'];
        
        if ($authorization->is_super_admin($user_id)) {
            // Si el usuario es super administrador, mostrar todos los tópicos, reportes y gráficos
            $topics = $this->topicModel->get_topics();
            $reports = $this->reportModel->get_reports();
            $charts = $this->chartModel->get_charts();
        } else {
            // Si el usuario no es super administrador, mostrar solo los tópicos, reportes y gráficos asignados
            $topics = $this->topicModel->get_assigned_topics($user_id);
            $reports = $this->reportModel->get_assigned_reports($user_id);
            $charts = $this->chartModel->get_assigned_charts($user_id);
        }

        // Renderizar la vista del dashboard con los tópicos correspondientes
        require_once 'views/dashboard.php';
    }



    /**
     * Función error404
     *
     * Muestra la vista de error 404.
     */
    public function error404()
    {
        require_once 'views/error404.php';
    }
}
