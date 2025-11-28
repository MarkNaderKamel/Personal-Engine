<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Project;
use App\Models\Task;
use App\Models\Gamification;

class ProjectController
{
    private $projectModel;
    private $taskModel;
    private $gamification;

    public function __construct()
    {
        $this->projectModel = new Project();
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $projects = $this->projectModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/projects/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /projects');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'project_name' => Security::sanitizeInput($_POST['project_name']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'status' => 'active',
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null
            ];

            if ($this->projectModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 20, 'project_created', 'Created a project');
                $_SESSION['success'] = 'Project created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create project';
            }

            header('Location: /projects');
            exit;
        }

        require __DIR__ . '/../Views/modules/projects/create.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        $project = $this->projectModel->findById($id);
        
        if (!$project || $project['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Project not found');
        }

        $tasks = $this->taskModel->getTasksByProject($id);
        require __DIR__ . '/../Views/modules/projects/view.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /projects');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /projects');
            exit;
        }

        $project = $this->projectModel->findById($id);
        
        if ($project && $project['user_id'] == $_SESSION['user_id']) {
            $this->projectModel->delete($id);
            $_SESSION['success'] = 'Project deleted successfully';
        }

        header('Location: /projects');
        exit;
    }
}
