<?php

class UserService {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUsers() {
        $result = $this->db->query("SELECT * FROM users");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $query = "SELECT * FROM users WHERE id = " . $id;
        $result = $this->db->query($query);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($name, $email) {
        $query = "INSERT INTO users (name, email) VALUES ('" . $name . "', '" . $email . "')";
        $this->db->query($query);
        return true;
    }

    // deleteUser not implemented yet
}
