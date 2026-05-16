<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

/**
 * Model for managing blog posts.
 */
Class Post {
    /**
     * @var PDO $db Database connection instance
     */
    private PDO $db;

    /**
     * Initializes database connection.
     */
    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Get all posts ordered by creation date.
     *
     * @return array List of all posts, newest first
     */
    public function getAll(): array {
        $stmt = $this->db->query('
            SELECT * FROM posts ORDER BY created_at DESC
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single post by ID.
     *
     * @param int $id Post ID
     * @return array|false Post data or false if not found
     */
    public function getById(int $id): array|false {
        $stmt = $this->db->prepare('
            SELECT * FROM posts WHERE id = ?
        ');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new post.
     *
     * @param array $data Post data containing user_id, title, content
     * @return bool True on success, false on failure
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)
        ');
        return $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['content']
        ]);
    }

    /**
     * Update an existing post.
     *
     * @param int $id Post ID
     * @param array $data Updated data containing title, content
     * @return bool True on success, false on failure
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('
            UPDATE posts SET title = ?, content = ? WHERE id = ?
        ');
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $id
        ]);
    }

    /**
     * Delete a post by ID.
     *
     * @param int $id Post ID
     * @return bool True on success, false on failure
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('
            DELETE FROM posts WHERE id = ?
        ');
        return $stmt->execute([$id]);
    }
}