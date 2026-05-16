<?php 

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

/**
 * Model for managing blog post comments.
 */
Class Comment {
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
     * Get all comments for a specific post with author names.
     *
     * @param int $postId Post ID
     * @return array List of comments with author names, ordered oldest first
     */
    public function getByPostId(int $postId): array {
        $stmt = $this->db->prepare('
            SELECT comments.*, users.name as author
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.post_id = ?
            ORDER BY comments.created_at ASC
        ');
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new comment.
     *
     * @param array $data Comment data containing post_id, user_id, text
     * @return bool True on success, false on failure
     */
    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO comments (post_id, user_id, text) VALUES (?, ?, ?)
        ');
        return $stmt->execute([
            $data['post_id'],
            $data['user_id'],
            $data['text']
        ]);
    }
}