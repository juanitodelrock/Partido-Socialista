<?php

require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/Chart.php';

class DashboardController
{
    private $topicModel;
    private $reportModel;
    private $chartModel;

    public function __construct()
    {
        $this->topicModel = new Topic(Database::getConnection());
        $this->reportModel = new Report(Database::getConnection());
        $this->chartModel = new Chart(Database::getConnection());
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /app/?action=login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user_topics = $this->topicModel->get_user_topics($userId);
        $user_reports = $this->reportModel->get_user_reports($userId);
        $user_charts = $this->chartModel->get_user_charts($userId);

        require_once 'views/dashboard.php';
    }
}
