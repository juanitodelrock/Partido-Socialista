<?php
// app/models/Chart.php

class Chart
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function get_charts()
    {
        $sql = "SELECT * FROM charts";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $charts = array();
            while ($row = $result->fetch_assoc()) {
                $charts[] = $row;
            }
            return $charts;
        } else {
            return null;
        }
    }

    public function get_assigned_charts($user_id)
    {
        $sql = "SELECT c.* FROM charts c
                JOIN assigned_charts ac ON c.id = ac.chart_id
                WHERE ac.user_id = $user_id";
        $result = $this->connection->query($sql);

        if ($result->num_rows > 0) {
            $charts = array();
            while ($row = $result->fetch_assoc()) {
                $charts[] = $row;
            }
            return $charts;
        } else {
            return null;
        }
    }
}
