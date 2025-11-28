<?php

namespace App\Controllers;

use App\Models\Password;
use App\Models\Gamification;
use App\Core\Security;

class PasswordController
{
    private $passwordModel;
    private $gamification;

    public function __construct()
    {
        $this->passwordModel = new Password();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $passwords = $this->passwordModel->getAllForUser($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/passwords/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /passwords');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_name' => Security::sanitizeInput($_POST['service_name']),
                'username' => Security::sanitizeInput($_POST['username'] ?? ''),
                'password' => $_POST['password'],
                'url' => Security::sanitizeInput($_POST['url'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->passwordModel->createPassword($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'password_added', 'Added a password');
                $_SESSION['success'] = 'Password added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add password';
            }

            header('Location: /passwords');
            exit;
        }

        require __DIR__ . '/../Views/modules/passwords/create.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        
        header('Content-Type: application/json');
        
        if (!Security::verifyCSRFToken($_GET['csrf_token'] ?? '')) {
            echo json_encode(['error' => 'Invalid security token']);
            exit;
        }

        $password = $this->passwordModel->getDecryptedPassword($id, $_SESSION['user_id']);
        
        if ($password) {
            echo json_encode(['password' => $password['decrypted_password']]);
        } else {
            echo json_encode(['error' => 'Password not found']);
        }
        exit;
    }

    public function edit($id)
    {
        Security::requireAuth();
        $password = $this->passwordModel->findById($id);
        
        if (!$password || $password['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Password not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /passwords');
                exit;
            }

            $data = [
                'service_name' => Security::sanitizeInput($_POST['service_name']),
                'username' => Security::sanitizeInput($_POST['username'] ?? ''),
                'url' => Security::sanitizeInput($_POST['url'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }

            if ($this->passwordModel->updatePassword($id, $data, $_SESSION['user_id'])) {
                $_SESSION['success'] = 'Password updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update password';
            }

            header('Location: /passwords');
            exit;
        }

        require __DIR__ . '/../Views/modules/passwords/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /passwords');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /passwords');
            exit;
        }

        $password = $this->passwordModel->findById($id);
        
        if ($password && $password['user_id'] == $_SESSION['user_id']) {
            $this->passwordModel->delete($id);
            $_SESSION['success'] = 'Password deleted successfully';
        }

        header('Location: /passwords');
        exit;
    }
}
