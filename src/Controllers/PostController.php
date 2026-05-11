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

    public function create(): void {
        require_once __DIR__ . '/../Views/posts/create.php';
    }

    public function store(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $errors  = [];

        if (empty($title)) {
            $errors[] = 'Заголовок обязателен';
        }

        if (strlen($title) > 255) {
            $errors[] = 'Заголовок не должен превышать 255 символов';
        }

        if (empty($content)) {
            $errors[] = 'Содержимое обязательно';
        }

        if (!empty($errors)) {
            require_once __DIR__ . '/../Views/posts/create.php';
            return;
        }

        $this->postModel->create([
            'user_id'   => $_SESSION['user_id'],
            'title'     => $_POST['title'],
            'content'   => $_POST['content']
        ]);

        header('Location: /');;
    }
}
