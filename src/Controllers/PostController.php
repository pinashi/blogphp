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

    /**
     * @param int $id Post ID
     */
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
        
        $errors  = $this->validate($_POST);

        if (!empty($errors)) {
            require_once __DIR__ . '/../Views/posts/create.php';
            return;
        }

        $this->postModel->create([
            'user_id'   => $_SESSION['user_id'],
            'title'     => $_POST['title'],
            'content'   => $_POST['content']
        ]);

        header('Location: /');
    }

    public function edit(int $id): void {
        $post = $this->checkPostAccess($id);

        require_once __DIR__ . '/../Views/posts/edit.php';
    }

    public function update(int $id): void {
        $post = $this->checkPostAccess($id);
        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $errors  = $this->validate($_POST);

        if (!empty($errors)) {
            require_once __DIR__ . '/../Views/posts/edit.php';
            return;
        }

        $this->postModel->update($id, [
            'title'   => $title,
            'content' => $content
        ]);

        header('Location: /post/' . $id);
    }

    public function destroy(int $id): void {
        $this->checkPostAccess($id);
        $this->postModel->delete($id);
        header('Location: /');
    }

    private function checkPostAccess(int $id): array {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $post = $this->postModel->getById($id);

        if (!$post) {
            http_response_code(404);
            require_once __DIR__ . '/../Views/404.php';
            exit;
        }

        if ($_SESSION['user_id'] !== (int)$post['user_id']) {
            http_response_code(403);
            echo 'Нет доступа';
            exit;
        }

        return $post;
    }

    private function validate(array $data): array {
        $errors = [];

        if (empty(trim($data['title'] ?? ''))) {
            $errors[] = 'Заголовок обязателен';
        }

        if (strlen($data['title'] ?? '') > 255) {
            $errors[] = 'Заголовок не должен превышать 255 символов';
        }

        if (empty(trim($data['content'] ?? ''))) {
            $errors[] = 'Содержимое обязательно';
        }

        return $errors;
    }   
}
