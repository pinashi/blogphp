<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Post.php';
require_once __DIR__ . '/../Models/Comment.php';

/**
 * Handles all blog post operations: listing, viewing, creating, editing, and deleting.
 */
class PostController {
    /**
     * @var Post $postModel Post model instance
     */
    private Post $postModel;

    /**
     * Initializes session and post model.
     */
    public function __construct() {
        $this->postModel = new Post();
    }

    /**
     * Check if user has access to modify the post.
     * Redirects to login if not authenticated.
     * Returns 404 if post not found.
     * Returns 403 if user is not the post author.
     *
     * @param int $id Post ID
     * @return array Post data
     */
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
    
    /**
     * Validate post form data.
     *
     * @param array $data Form data containing title and content
     * @return array List of validation errors, empty if valid
     */
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

    /**
     * Display list of all posts.
     *
     * @return void
     */
    public function index(): void {
        $posts = $this->postModel->getAll();
        require_once __DIR__ . '/../Views/posts/index.php';
    }
        
    /**
     * Display a single post with comments.
     *
     * @param int $id Post ID
     * @return void
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

    /**
     * Display post creation form.
     * Requires authentication.
     *
     * @return void
     */
    public function create(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            return;
        }
        
        require_once __DIR__ . '/../Views/posts/create.php';
    }
    
    /**
     * Handle post creation form submission.
     * Requires authentication.
     *
     * @return void
     */
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

    /**
     * Display post edit form.
     * Requires authentication and post ownership.
     *
     * @param int $id Post ID
     * @return void
     */
    public function edit(int $id): void {
        $post = $this->checkPostAccess($id);

        require_once __DIR__ . '/../Views/posts/edit.php';
    }

    /**
     * Handle post update form submission.
     * Requires authentication and post ownership.
     *
     * @param int $id Post ID
     * @return void
     */
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

    /**
     * Delete a post.
     * Requires authentication and post ownership.
     *
     * @param int $id Post ID
     * @return void
     */
    public function destroy(int $id): void {
        $this->checkPostAccess($id);
        $this->postModel->delete($id);
        header('Location: /');
    }
    
}
