<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Models/Comment.php';

class PostController {
    private Post $postModel;

    public function __construct()
    {
        session_start();
        $this->postModel = new Post();
    }

    /**
     * Home - all posts
     */
    public function index(): void {
        $posts = $this->postModel->getAll();
        require_once __DIR__ . '/../Views/posts/index.php';
    }

    public function show(int $id): void {
        $post = $this->postModel->getById($id);
        
        if (!$post) {
            http_response_code(404);
            require_once __DIR__ . '/../Views/404.php';
            return;
        }

        $commentModel = new Comment();
        $comments     = $commentModel->getByPostId($id);

        require_once __DIR__ . '/../Views/posts/show.php';
    }
}