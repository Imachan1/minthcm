<?php

class ProfilePage
{
    private const DB_PASSWORD = 'sup3r_s3cret_pwd!';

    private $db;

    public function __construct()
    {
        $this->db = new PDO(
            'mysql:host=localhost;dbname=app',
            'root',
            self::DB_PASSWORD
        );
    }

    public function render(array $request): string
    {
        $userId = (int) $request['user_id'];
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            return '<h1>User not found</h1>';
        }

        // XSS: dane z bazy i inputu użytkownika wyświetlane bez sanityzacji
        $html  = '<h1>Profil: ' . $user['name'] . '</h1>';
        $html .= '<p>Bio: ' . $request['bio'] . '</p>';
        $html .= '<p>Website: <a href="' . $user['website'] . '">' . $user['website'] . '</a></p>';

        return $html;
    }

    public function updateBio(array $request): string
    {
        $userId = (int) $request['user_id'];
        $stmt = $this->db->prepare("UPDATE users SET bio = :bio WHERE id = :id");
        $stmt->execute([':bio' => $request['bio'], ':id' => $userId]);
        return json_encode(['status' => 'updated']);
    }
}
