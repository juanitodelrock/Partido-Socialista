<?php
// app/models/Topic.php
require_once 'database.php';
require_once 'User.php';

class Topic {
    private $connection;
    private $userModel;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->userModel = new User($connection);
    }

    public function get_all_topics() {
        $sql = "SELECT * FROM topics";
        $result = $this->connection->query($sql);
        $topics = [];
        while ($topic = $result->fetch_assoc()) {
            $topics[] = $topic;
        }
        return $topics;
    }

    public function get_topic_by_id($id) {
        $sql = "SELECT * FROM topics WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $topic = $result->fetch_assoc();
        $stmt->close();
        return $topic;
    }

    public function create_topic($name) {
        $sql = "INSERT INTO topics (name) VALUES (?)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->close();
    }

    public function delete_topic($id) {
        $sql = "DELETE FROM topics WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function update_topic($id, $name) {
        $sql = "UPDATE topics SET name = ? WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function get_topics_by_user($user_id) {
        $sql = "SELECT t.* FROM topics t ";
        if (!$this->userModel->is_super_admin($user_id)) {
            $sql .= "INNER JOIN topic_user tu ON t.id = tu.topic_id ";
            $sql .= "WHERE tu.user_id = ?";
        }
        $stmt = $this->connection->prepare($sql);
        if (!$this->userModel->is_super_admin($user_id)) {
            $stmt->bind_param("i", $user_id);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $topics = [];
        while ($topic = $result->fetch_assoc()) {
            $topics[] = $topic;
        }
        $stmt->close();
        return $topics;
    }
    public function get_topics() {
        $sql = "SELECT * FROM topics";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $topics = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        return $topics;
    }
    
    public function get_assigned_topics($user_id) {
        $sql = "SELECT * FROM assigned_topics WHERE user_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $assigned_topics = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    
        $topics = [];
        foreach ($assigned_topics as $assigned_topic) {
            $sql = "SELECT * FROM topics WHERE id = ?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("i", $assigned_topic['topic_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $topic = $result->fetch_assoc();
            $stmt->close();
    
            if ($topic) {
                array_push($topics, $topic);
            }
        }
    
        return $topics;
    }
    /**
     * Get all topics assigned to a user
     * @param int $user_id
     * @return array
     */
    public function save_topic_meta($topic_id, $network, $data) {
        $query = "INSERT INTO topics_meta (topic_id, network, data) VALUES (?, ?, ?)";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("iss", $topic_id, $network, $data);
        $stmt->execute();
        $stmt->close();

        return $this->connection->insert_id;
    }
    
    
}