<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/User.php';

/**
 * Handles user authentication: registration, login, and logout.
 */
Class AuthController {
    /**
     * @var User $userModel User model instance
     */
    private User $userModel;

    /**
     * Initializes session and user model.
     */
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Display registration form.
     *
     * @return void
     */
    public function register(): void {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    /**
     * Handle registration form submission.
     * Validates email uniqueness and creates new user.
     *
     * @return void
     */
    public function registerStore(): void {
        $name       = $_POST['name'];
        $email      = $_POST['email'];
        $password   = $_POST['password'];

        if($this->userModel->getByEmail($email)) {
            $error = 'Email уже занят';
            require_once __DIR__ . '/../Views/auth/register.php';
            return;
        }

        $this->userModel->create([
            'name'      => $name,
            'email'     => $email,
            'password'  => $password
        ]);

        header('Location: /login');
    }

    /**
     * Display login form.
     *
     * @return void
     */
    public function login(): void {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    /**
     * Handle login form submission.
     * Verifies credentials and starts user session.
     *
     * @return void
     */
    public function loginStore(): void {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->userModel->getByEmail($email);

        if(!$user || !password_verify($password, $user['password'])) {
            $error = 'Неверный email или пароль';
            require_once __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header('Location: /');
    }

    /**
     * Destroy session and redirect to home page.
     *
     * @return void
     */
    public function logout(): void {
        session_destroy();
        header('Location: /');
    }
}