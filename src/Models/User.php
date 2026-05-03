<?php

require_once __DIR__ . '/../config/database.php';

Class User {
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }


    /**
     * @param string $email User email
     * @return array|false User or false
     */
    public function getByEmail(string $email): array|false {
        $stmt = $this->db->prepare('
            SELECT * FROM users WHERE email = ?
        ');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO users (name, email, password) VALUES (?, ?, ?)
        ');
        return $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }
}