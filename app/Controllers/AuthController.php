<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Gamification;
use App\Core\Security;

class AuthController
{
    private $userModel;
    private $gamification;

    public function __construct()
    {
        $this->userModel = new User();
        $this->gamification = new Gamification();
    }

    public function showLogin()
    {
        if (Security::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /login');
            exit;
        }

        $email = Security::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];

        $this->gamification->updateStreak($user['id']);
        $this->gamification->addXP($user['id'], 10, 'login', 'Daily login');

        header('Location: /dashboard');
        exit;
    }

    public function showRegister()
    {
        if (Security::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /register');
            exit;
        }

        $email = Security::sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $firstName = Security::sanitizeInput($_POST['first_name'] ?? '');
        $lastName = Security::sanitizeInput($_POST['last_name'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            header('Location: /register');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = 'Email already exists';
            header('Location: /register');
            exit;
        }

        $userId = $this->userModel->register([
            'email' => $email,
            'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => 'user'
        ]);

        if ($userId) {
            $_SESSION['success'] = 'Registration successful! Please log in.';
            header('Location: /login');
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: /register');
        }
        exit;
    }

    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /dashboard');
            exit;
        }

        session_destroy();
        header('Location: /login');
        exit;
    }

    public function showProfile()
    {
        Security::requireAuth();
        $user = $this->userModel->findById($_SESSION['user_id']);
        $stats = $this->gamification->getUserStats($_SESSION['user_id']);
        $badges = $this->gamification->getUserBadges($_SESSION['user_id']);
        require __DIR__ . '/../Views/auth/profile.php';
    }

    public function updateProfile()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /profile');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /profile');
            exit;
        }

        $data = [
            'first_name' => Security::sanitizeInput($_POST['first_name'] ?? ''),
            'last_name' => Security::sanitizeInput($_POST['last_name'] ?? '')
        ];

        if ($this->userModel->updateProfile($_SESSION['user_id'], $data)) {
            $_SESSION['success'] = 'Profile updated successfully';
            $this->gamification->addXP($_SESSION['user_id'], 5, 'profile_update', 'Updated profile');
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }

        header('Location: /profile');
        exit;
    }
}
