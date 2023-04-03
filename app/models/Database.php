<?php
// app/models/database.php

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {

        $this->connection = new mysqli(BD_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            die('Error de conexión: ' . $this->connection->connect_error);
        }
    }

    public static function getConnection() {
        $database = new Database();
        return $database->connection;
    }
}
