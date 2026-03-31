<?php

class TaskManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTasks($userId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($title, $userId) {
        $stmt = $this->db->prepare("INSERT INTO tasks (title, user_id) VALUES (?, ?)");
        $stmt->execute([$title, $userId]);
    }
}
