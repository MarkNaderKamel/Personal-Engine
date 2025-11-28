<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Task;
use App\Models\Gamification;

class TaskController
{
    private $taskModel;
    private $gamification;

    public function __construct()
    {
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $tasks = $this->taskModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/tasks/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /tasks');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => Security::sanitizeInput($_POST['title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => 'pending',
                'due_date' => $_POST['due_date'] ?? null
            ];

            if ($this->taskModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'task_created', 'Created a new task');
                $_SESSION['success'] = 'Task created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create task';
            }

            header('Location: /tasks');
            exit;
        }

        require __DIR__ . '/../Views/modules/tasks/create.php';
    }

    public function complete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /tasks');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /tasks');
            exit;
        }

        if ($this->taskModel->markComplete($id, $_SESSION['user_id'])) {
            $this->gamification->addXP($_SESSION['user_id'], 20, 'task_completed', 'Completed a task');
            $_SESSION['success'] = 'Task completed!';
        }

        header('Location: /tasks');
        exit;
    }

    public function edit($id)
    {
        Security::requireAuth();
        
        $task = $this->taskModel->findById($id);
        
        if (!$task || $task['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Task not found';
            header('Location: /tasks');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /tasks');
                exit;
            }

            $data = [
                'title' => Security::sanitizeInput($_POST['title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => $_POST['status'] ?? 'pending',
                'due_date' => $_POST['due_date'] ?? null
            ];

            if ($this->taskModel->update($id, $data)) {
                $_SESSION['success'] = 'Task updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update task';
            }

            header('Location: /tasks');
            exit;
        }

        require __DIR__ . '/../Views/modules/tasks/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /tasks');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /tasks');
            exit;
        }

        $task = $this->taskModel->findById($id);
        
        if ($task && $task['user_id'] == $_SESSION['user_id']) {
            $this->taskModel->delete($id);
            $_SESSION['success'] = 'Task deleted successfully';
        }

        header('Location: /tasks');
        exit;
    }
}
