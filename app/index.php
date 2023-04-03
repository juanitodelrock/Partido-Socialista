<?php
// app/index.php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/controllers/HomeController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

$controller = new HomeController();

if (method_exists($controller, $action)) {
    // Incluir header.php antes de la vista
    require_once 'views/header.php';

    // Llamar al método del controlador
    $controller->$action();

    // Incluir footer.php después de la vista
    require_once 'views/footer.php';
} else {
    header('Location: /app/?action=error404&message=' . $action . ' no existe');
}
