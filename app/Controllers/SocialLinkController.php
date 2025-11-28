<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\SocialLink;
use App\Models\Gamification;

class SocialLinkController
{
    private $model;
    private $gamification;

    public function __construct()
    {
        $this->model = new SocialLink();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $links = $this->model->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/social/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /social-links');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'platform' => Security::sanitizeInput($_POST['platform']),
                'profile_url' => Security::sanitizeInput($_POST['profile_url']),
                'username' => Security::sanitizeInput($_POST['username'] ?? ''),
                'is_public' => isset($_POST['is_public']),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 5, 'social_added', 'Added a social link');
                $_SESSION['success'] = 'Social link added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add social link';
            }

            header('Location: /social-links');
            exit;
        }

        require __DIR__ . '/../Views/modules/social/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $link = $this->model->findById($id);
        
        if (!$link || $link['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Social link not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /social-links');
                exit;
            }

            $data = [
                'platform' => Security::sanitizeInput($_POST['platform']),
                'profile_url' => Security::sanitizeInput($_POST['profile_url']),
                'username' => Security::sanitizeInput($_POST['username'] ?? ''),
                'is_public' => isset($_POST['is_public']),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = 'Social link updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update social link';
            }

            header('Location: /social-links');
            exit;
        }

        require __DIR__ . '/../Views/modules/social/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /social-links');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /social-links');
            exit;
        }

        $link = $this->model->findById($id);
        if ($link && $link['user_id'] == $_SESSION['user_id']) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Social link deleted successfully';
        }

        header('Location: /social-links');
        exit;
    }
}
