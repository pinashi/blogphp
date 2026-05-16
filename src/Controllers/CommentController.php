<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Comment.php';

/**
 * Handles comment creation for blog posts.
 */
Class CommentController {
    /**
     * @var Comment $commentModel Comment model instance
     */
    private Comment $commentModel;

    /**
     * Initializes session and comment model.
     */
    public function __construct() {
        $this->commentModel = new Comment();
    }

    /**
     * Handle comment form submission.
     * Requires authentication. Redirects back to post after saving.
     *
     * @param int $postId ID of the post to comment on
     * @return void
     */
    public function store($postId): void {
        if (!isset($_SESSION['user_id'])){
            header('Location: /login');
            return;
        }

        $text = trim($_POST['text'] ?? '');

        if (empty($text)) {
            header('Location: /post/' . $postId);
            return;
        }

        $this->commentModel->create([
            'post_id'   => $postId,
            'user_id'   => $_SESSION['user_id'],
            'text'      => $_POST['text']
        ]);

        header('Location: /post/' . $postId);
    }
}