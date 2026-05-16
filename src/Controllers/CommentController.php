<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Comment.php';

Class CommentController {
    private Comment $commentModel;

    public function __construct() {
        session_start();
        $this->commentModel = new Comment();
    }

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