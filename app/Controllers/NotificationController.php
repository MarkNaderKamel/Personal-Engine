<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Notification;

class NotificationController
{
    private $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        Security::requireAuth();
        $notifications = $this->notificationModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/notifications/index.php';
    }

    public function markRead($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /notifications');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /notifications');
            exit;
        }

        $this->notificationModel->markAsRead($id, $_SESSION['user_id']);
        header('Location: /notifications');
        exit;
    }

    public function markAllRead()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /notifications');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /notifications');
            exit;
        }

        $this->notificationModel->markAllAsRead($_SESSION['user_id']);
        header('Location: /notifications');
        exit;
    }

    public function getUnread()
    {
        Security::requireAuth();
        $notifications = $this->notificationModel->getUnreadNotifications($_SESSION['user_id']);
        header('Content-Type: application/json');
        echo json_encode($notifications);
        exit;
    }
}
