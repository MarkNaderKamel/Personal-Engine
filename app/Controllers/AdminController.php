<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\User;
use App\Core\Database;

class AdminController
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->userModel = new User();
        $this->db = Database::getInstance();
    }

    public function index()
    {
        Security::requireAdmin();
        
        $stats = [
            'total_users' => $this->userModel->count(),
            'total_tasks' => $this->db->fetchOne("SELECT COUNT(*) as count FROM tasks")['count'],
            'total_bills' => $this->db->fetchOne("SELECT COUNT(*) as count FROM bills")['count'],
            'total_documents' => $this->db->fetchOne("SELECT COUNT(*) as count FROM documents")['count']
        ];
        
        require __DIR__ . '/../Views/admin/index.php';
    }

    public function users()
    {
        Security::requireAdmin();
        $users = $this->userModel->getAllUsers();
        require __DIR__ . '/../Views/admin/users.php';
    }

    public function deleteUser($id)
    {
        Security::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/users');
            exit;
        }

        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Cannot delete your own account';
        } else {
            $this->userModel->deleteUser($id);
            $_SESSION['success'] = 'User deleted successfully';
        }
        
        header('Location: /admin/users');
        exit;
    }

    public function logs()
    {
        Security::requireAdmin();
        $logs = $this->db->fetchAll("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 100");
        require __DIR__ . '/../Views/admin/logs.php';
    }
}
