<?php
// app/models/Report.php

class Report
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function get_reports()
    {
        $sql = "SELECT * FROM reports";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $reports = array();
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
            return $reports;
        } else {
            return null;
        }
    }

    public function get_assigned_reports($user_id)
    {
        $sql = "SELECT r.* FROM reports r
                JOIN assigned_reports ar ON r.id = ar.report_id
                WHERE ar.user_id = $user_id";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $reports = array();
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
            return $reports;
        } else {
            return null;
        }
    }
}
