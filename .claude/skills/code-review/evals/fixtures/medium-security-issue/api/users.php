<?php

/**
 * User API Controller
 */
class UserApiController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users");
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

    public function deleteUser($id) {
        $this->db->query("DELETE FROM users WHERE id = " . $id);
        return ['status' => 'deleted'];
    }
}
