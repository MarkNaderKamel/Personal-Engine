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
            $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
            $this->gamification->addXP($_SESSION['user_id'], 5, 'profile_update', 'Updated profile');
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }

        header('Location: /profile');
        exit;
    }

    public function showForgotPassword()
    {
        if (Security::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }
        require __DIR__ . '/../Views/auth/forgot-password.php';
    }

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /forgot-password');
            exit;
        }

        $email = Security::sanitizeInput($_POST['email'] ?? '');

        if (empty($email)) {
            $_SESSION['error'] = 'Email is required';
            header('Location: /forgot-password');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = Security::generateRandomToken();
            $this->userModel->createPasswordResetToken($email);
            $_SESSION['success'] = 'If your email exists in our system, you will receive a password reset link. For this demo, use the token displayed in the URL to reset your password.';
        } else {
            $_SESSION['success'] = 'If your email exists in our system, you will receive a password reset link.';
        }

        header('Location: /login');
        exit;
    }

    public function showResetPassword()
    {
        if (Security::isAuthenticated()) {
            header('Location: /dashboard');
            exit;
        }
        
        $token = $_GET['token'] ?? '';
        require __DIR__ . '/../Views/auth/reset-password.php';
    }

    public function resetPassword()
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

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token) || empty($password)) {
            $_SESSION['error'] = 'Token and password are required';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password must be at least 8 characters';
            header('Location: /reset-password?token=' . urlencode($token));
            exit;
        }

        if ($this->userModel->resetPassword($token, $password)) {
            $_SESSION['success'] = 'Password has been reset successfully. Please login with your new password.';
            header('Location: /login');
        } else {
            $_SESSION['error'] = 'Invalid or expired reset token';
            header('Location: /forgot-password');
        }
        exit;
    }

    public function changePassword()
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

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = $this->userModel->findById($_SESSION['user_id']);

        if (!Security::verifyPassword($currentPassword, $user['password'])) {
            $_SESSION['error'] = 'Current password is incorrect';
            header('Location: /profile');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'New passwords do not match';
            header('Location: /profile');
            exit;
        }

        if (strlen($newPassword) < 8) {
            $_SESSION['error'] = 'New password must be at least 8 characters';
            header('Location: /profile');
            exit;
        }

        if ($this->userModel->updateProfile($_SESSION['user_id'], [
            'password' => Security::hashPassword($newPassword)
        ])) {
            $_SESSION['success'] = 'Password changed successfully';
            $this->gamification->addXP($_SESSION['user_id'], 10, 'password_change', 'Changed password');
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }

        header('Location: /profile');
        exit;
    }
}
