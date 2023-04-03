<?php
// app/controllers/TopicController.php
require_once __DIR__ . '/../models/Topic.php';
require_once __DIR__ . '/../models/User.php';

class TopicController {
    private $topicModel;
    private $userModel;

    public function __construct($connection) {
        $this->topicModel = new Topic($connection);
        $this->userModel = new User($connection);
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        $topics = $this->topicModel->get_topics_by_user($user_id);
        require_once 'views/header.php';
        require_once 'views/topics/index.php';
        require_once 'views/footer.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
            $name = $_POST['name'];
            $this->topicModel->create_topic($name);
            header('Location: /app/topics/index.php');
        } else {
            require_once 'views/header.php';
            require_once 'views/topics/create.php';
            require_once 'views/footer.php';
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
            $name = $_POST['name'];
            $this->topicModel->update_topic($id, $name);
            header('Location: /app/topics/');
        } else {
            $topic = $this->topicModel->get_topic_by_id($id);
            if (!$topic) {
                $_SESSION['error_message'] = 'El tópico no existe';
                header('Location: /app/topics/');
                exit();
            }
            require_once 'views/header.php';
            require_once 'views/topics/edit.php';
            require_once 'views/footer.php';
        }
    }

    public function delete($id) {
        $this->topicModel->delete_topic($id);
        header('Location: /app/topics/');
    }
}
