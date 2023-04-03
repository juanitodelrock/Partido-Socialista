<?php
// app/models/User.php

/**
 * Clase User: modela un usuario de la aplicación.
 */
class User {
    private $connection;

    /**
     * Constructor de la clase User.
     *
     * @param mysqli $connection Conexión a la base de datos.
     */
    public function __construct($connection) {
        $this->connection = $connection;
    }

    /**
     * Comprueba si existe un usuario con el correo electrónico dado.
     *
     * @param string $email Correo electrónico a comprobar.
     * @return boolean true si existe un usuario con el correo electrónico dado, false si no.
     */
    public function check_user_exists($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Crea un nuevo usuario en la base de datos con los valores dados.
     *
     * @param string $name Nombre del usuario.
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario (debe estar hasheada).
     * @param int $country_id ID del país del usuario (opcional, por defecto 1).
     */
    public function create_user($name, $email, $password, $country_id = 1) {
        $sql = "INSERT INTO users (name, email, password, country_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $password, $country_id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Valida el correo electrónico y la contraseña de un usuario y devuelve el usuario si son correctos.
     *
     * @param string $email Correo electrónico del usuario.
     * @param string $password Contraseña del usuario (sin hashear).
     * @return array|null Devuelve un array con los datos del usuario si el correo electrónico y la contraseña son correctos, o null si no.
     */
    public function validate_user($email, $password) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    
        if ($user && password_verify($password, $user['password']) && $user['estado'] == 'Activo' && $user['super_administrador'] == 1) {
            return $user;
        } else {
            return null;
        }
    }



    public function is_super_admin($user_id)
    {
        $stmt = $this->connection->prepare('SELECT * FROM users WHERE id = ? AND super_administrador = 1');
        $stmt->execute([$user_id]);
        return $stmt->fetch();
    }


    public function user_has_access($user_id, $topic_id)
    {
        $stmt = $this->db->prepare('SELECT * FROM assigned_topics WHERE user_id = ? AND topic_id = ?');
        $stmt->execute([$user_id, $topic_id]);
        return $stmt->fetch();
    }

}
