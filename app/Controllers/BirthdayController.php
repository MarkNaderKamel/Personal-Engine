<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Birthday;
use App\Models\Gamification;

class BirthdayController
{
    private $model;
    private $gamification;

    public function __construct()
    {
        $this->model = new Birthday();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $birthdays = $this->model->findByUserId($_SESSION['user_id']);
        $upcoming = $this->model->getUpcoming($_SESSION['user_id'], 10);
        $today = $this->model->getTodayBirthdays($_SESSION['user_id']);
        $thisMonth = $this->model->getThisMonth($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/birthdays/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /birthdays');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'person_name' => Security::sanitizeInput($_POST['person_name']),
                'birth_date' => $_POST['birth_date'],
                'relationship' => Security::sanitizeInput($_POST['relationship'] ?? ''),
                'reminder_days' => intval($_POST['reminder_days'] ?? 7),
                'gift_ideas' => Security::sanitizeInput($_POST['gift_ideas'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'birthday_added', 'Added a birthday reminder');
                $_SESSION['success'] = 'Birthday added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add birthday';
            }

            header('Location: /birthdays');
            exit;
        }

        require __DIR__ . '/../Views/modules/birthdays/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $birthday = $this->model->findById($id);
        
        if (!$birthday || $birthday['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Birthday not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /birthdays');
                exit;
            }

            $data = [
                'person_name' => Security::sanitizeInput($_POST['person_name']),
                'birth_date' => $_POST['birth_date'],
                'relationship' => Security::sanitizeInput($_POST['relationship'] ?? ''),
                'reminder_days' => intval($_POST['reminder_days'] ?? 7),
                'gift_ideas' => Security::sanitizeInput($_POST['gift_ideas'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = 'Birthday updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update birthday';
            }

            header('Location: /birthdays');
            exit;
        }

        require __DIR__ . '/../Views/modules/birthdays/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /birthdays');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /birthdays');
            exit;
        }

        $birthday = $this->model->findById($id);
        if ($birthday && $birthday['user_id'] == $_SESSION['user_id']) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Birthday deleted successfully';
        }

        header('Location: /birthdays');
        exit;
    }
}
