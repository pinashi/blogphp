<?php

require_once __DIR__ . '/../Models/User.php';

Class AuthController {
    private User $userModel;

    public function __construct()
    {
        session_start();
        $this->userModel = new User();
    }

    // Registration form
    public function register(): void {
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    // Registration processing
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

    // Login form
    public function login(): void {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    // Login processing
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

    // Logout
    public function logout(): void {
        session_destroy();
        header('Location: /');
    }
}