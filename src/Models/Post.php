<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

Class Post {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * @return array All posts
     */
    public function getAll(): array {
        $stmt = $this->db->query(
            'SELECT * FROM posts ORDER BY created_at DESC'
            );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $id Post ID
     * @return array|false Post or false if not found
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare(
            'SELECT * FROM posts WHERE id = ?'
            );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data Post data
     * @return bool If sucsess 
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare(
            'INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)'
            );
        return $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['content']
        ]);
    }

    /**
     * @param int $id Post ID
     * @param array $data Updated post data
     * @return bool If sucsess
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare(
            'UPDATE posts SET title = ?, content = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $id
        ]);
    }

    /**
     * @param int $id Post ID
     * @return bool If sucsess
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare(
            'DELETE FROM posts WHERE id = ?'
        );
        return $stmt->execute([$id]);
    }
}