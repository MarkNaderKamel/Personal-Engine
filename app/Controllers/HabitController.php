<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\Gamification;

class HabitController
{
    private $habitModel;
    private $logModel;
    private $gamification;

    public function __construct()
    {
        $this->habitModel = new Habit();
        $this->logModel = new HabitLog();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $habits = $this->habitModel->findByUserId($_SESSION['user_id']);
        $stats = $this->habitModel->getStats($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/habits/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /habits');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'habit_name' => Security::sanitizeInput($_POST['habit_name']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'frequency' => $_POST['frequency'] ?? 'daily',
                'target_count' => intval($_POST['target_count'] ?? 1),
                'category' => Security::sanitizeInput($_POST['category'] ?? 'Health'),
                'color' => $_POST['color'] ?? '#667eea',
                'is_active' => true
            ];

            if ($this->habitModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'habit_created', 'Created a new habit');
                $_SESSION['success'] = 'Habit created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create habit';
            }

            header('Location: /habits');
            exit;
        }

        require __DIR__ . '/../Views/modules/habits/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $habit = $this->habitModel->findById($id);
        
        if (!$habit || $habit['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Habit not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /habits');
                exit;
            }

            $data = [
                'habit_name' => Security::sanitizeInput($_POST['habit_name']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'frequency' => $_POST['frequency'] ?? 'daily',
                'target_count' => intval($_POST['target_count'] ?? 1),
                'category' => Security::sanitizeInput($_POST['category'] ?? 'Health'),
                'color' => $_POST['color'] ?? '#667eea',
                'is_active' => isset($_POST['is_active'])
            ];

            if ($this->habitModel->update($id, $data)) {
                $_SESSION['success'] = 'Habit updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update habit';
            }

            header('Location: /habits');
            exit;
        }

        require __DIR__ . '/../Views/modules/habits/edit.php';
    }

    public function logHabit($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /habits');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /habits');
            exit;
        }

        $habit = $this->habitModel->findById($id);
        if (!$habit || $habit['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Habit not found';
            header('Location: /habits');
            exit;
        }

        $date = date('Y-m-d');
        $notes = Security::sanitizeInput($_POST['notes'] ?? '');
        
        if ($this->logModel->logCompletion($id, $date, 1, $notes)) {
            $this->gamification->addXP($_SESSION['user_id'], 10, 'habit_logged', 'Completed habit: ' . $habit['habit_name']);
            $_SESSION['success'] = 'Habit logged!';
        }

        header('Location: /habits');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /habits');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /habits');
            exit;
        }

        $habit = $this->habitModel->findById($id);
        if ($habit && $habit['user_id'] == $_SESSION['user_id']) {
            $this->habitModel->delete($id);
            $_SESSION['success'] = 'Habit deleted successfully';
        }

        header('Location: /habits');
        exit;
    }

    public function toggle($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /habits');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /habits');
            exit;
        }

        $habit = $this->habitModel->findById($id);
        if ($habit && $habit['user_id'] == $_SESSION['user_id']) {
            $this->habitModel->toggleActive($id);
            $_SESSION['success'] = 'Habit status updated';
        }

        header('Location: /habits');
        exit;
    }
}
