<?php

/**
 * User API Controller
 * Handles CRUD operations for the users resource
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchUsers($request) {
        $search = $request['query'];
        $query = "SELECT * FROM users WHERE name LIKE '%" . $search . "%' OR email LIKE '%" . $search . "%'";
        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($request) {
        $name = $request['name'];
        $email = $request['email'];
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return ['status' => 'created'];
    }

    public function updateUserEmail($id, $email) {
        $stmt = $this->db->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->execute([$email, $id]);
        return ['status' => 'updated'];
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }

    public function countUsers() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsersByIds(array $ids) {
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deactivateUser($id) {
        $stmt = $this->db->prepare("UPDATE users SET active = 0, deactivated_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);
        return ['status' => 'deactivated'];
    }

    public function getActiveUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE active = 1 ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Brak sprawdzenia autoryzacji — każda metoda jest dostępna bez weryfikacji sesji/tokenu
}




