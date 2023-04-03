<?php
// app/models/database.php

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($this->connection->connect_error) {
            die('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public static function getConnection() {
        try {
            $database = new Database();
            return $database->connection;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
