<?php 

require_once __DIR__ . '/../config/database.php';

Class Comment {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * @param int $postId Post ID
     * @return array Post comments
     */
    public function getByPostId(int $postId): array {
        $stmt = $this->db->prepare('
            SELECT comments.*, users.name as author
            FROM comments
            JOIN users ON comments.user_id = user_id
            WHERE comments.post_id = ?
            ORDER BY comments.created_at ASC
        ');
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}