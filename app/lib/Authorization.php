<?php
// app/lib/Authorization.php

class Authorization {
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function is_super_admin($user_id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return ($user && $user['super_administrador'] == 1);
    }
    public static function checkAccess($user) {
        if ($user && $user['super_administrador'] == 1) {
            return true;
        } else {
            return false;
        }
    }
}
