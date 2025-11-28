<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Goal;
use App\Models\GoalMilestone;
use App\Models\Gamification;

class GoalController
{
    private $goalModel;
    private $milestoneModel;
    private $gamification;

    public function __construct()
    {
        $this->goalModel = new Goal();
        $this->milestoneModel = new GoalMilestone();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $goals = $this->goalModel->findByUserId($_SESSION['user_id']);
        $stats = $this->goalModel->getStats($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/goals/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /goals');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'goal_title' => Security::sanitizeInput($_POST['goal_title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? 'Personal'),
                'target_date' => $_POST['target_date'] ?: null,
                'start_date' => $_POST['start_date'] ?: date('Y-m-d'),
                'status' => 'in_progress',
                'priority' => $_POST['priority'] ?? 'medium',
                'progress' => 0
            ];

            if ($this->goalModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 20, 'goal_created', 'Created a new goal');
                $_SESSION['success'] = 'Goal created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create goal';
            }

            header('Location: /goals');
            exit;
        }

        require __DIR__ . '/../Views/modules/goals/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $goal = $this->goalModel->findById($id);
        
        if (!$goal || $goal['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Goal not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /goals');
                exit;
            }

            $data = [
                'goal_title' => Security::sanitizeInput($_POST['goal_title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? 'Personal'),
                'target_date' => $_POST['target_date'] ?: null,
                'start_date' => $_POST['start_date'] ?: date('Y-m-d'),
                'status' => $_POST['status'] ?? 'in_progress',
                'priority' => $_POST['priority'] ?? 'medium',
                'progress' => intval($_POST['progress'] ?? 0)
            ];

            if ($this->goalModel->update($id, $data)) {
                if ($data['status'] === 'completed' && $goal['status'] !== 'completed') {
                    $this->gamification->addXP($_SESSION['user_id'], 100, 'goal_completed', 'Completed a goal!');
                }
                $_SESSION['success'] = 'Goal updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update goal';
            }

            header('Location: /goals');
            exit;
        }

        $milestones = $this->milestoneModel->findByGoalId($id);
        require __DIR__ . '/../Views/modules/goals/edit.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        $goal = $this->goalModel->findById($id);
        
        if (!$goal || $goal['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Goal not found');
        }

        $milestones = $this->milestoneModel->findByGoalId($id);
        require __DIR__ . '/../Views/modules/goals/view.php';
    }

    public function updateProgress($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /goals');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /goals');
            exit;
        }

        $goal = $this->goalModel->findById($id);
        if (!$goal || $goal['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Goal not found';
            header('Location: /goals');
            exit;
        }

        $progress = intval($_POST['progress'] ?? 0);
        if ($this->goalModel->updateProgress($id, $progress)) {
            if ($progress >= 100 && $goal['progress'] < 100) {
                $this->gamification->addXP($_SESSION['user_id'], 100, 'goal_completed', 'Completed a goal!');
            } else {
                $this->gamification->addXP($_SESSION['user_id'], 5, 'goal_progress', 'Updated goal progress');
            }
            $_SESSION['success'] = 'Progress updated successfully';
        }

        header('Location: /goals');
        exit;
    }

    public function addMilestone($goalId)
    {
        Security::requireAuth();
        $goal = $this->goalModel->findById($goalId);
        
        if (!$goal || $goal['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Goal not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /goals/edit/' . $goalId);
                exit;
            }

            $data = [
                'goal_id' => $goalId,
                'milestone_title' => Security::sanitizeInput($_POST['milestone_title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'target_date' => $_POST['target_date'] ?: null,
                'is_completed' => false
            ];

            if ($this->milestoneModel->create($data)) {
                $_SESSION['success'] = 'Milestone added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add milestone';
            }

            header('Location: /goals/edit/' . $goalId);
            exit;
        }

        require __DIR__ . '/../Views/modules/goals/add-milestone.php';
    }

    public function completeMilestone($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /goals');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /goals');
            exit;
        }

        $milestone = $this->milestoneModel->findById($id);
        if ($milestone) {
            $goal = $this->goalModel->findById($milestone['goal_id']);
            if ($goal && $goal['user_id'] == $_SESSION['user_id']) {
                $this->milestoneModel->markComplete($id);
                $this->gamification->addXP($_SESSION['user_id'], 15, 'milestone_completed', 'Completed a milestone');
                $_SESSION['success'] = 'Milestone completed!';
                
                $total = $this->milestoneModel->getTotalCount($milestone['goal_id']);
                $completed = $this->milestoneModel->getCompletedCount($milestone['goal_id']);
                if ($total > 0) {
                    $progress = round(($completed / $total) * 100);
                    $this->goalModel->updateProgress($milestone['goal_id'], $progress);
                }
            }
        }

        header('Location: /goals/edit/' . ($_POST['goal_id'] ?? ''));
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /goals');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /goals');
            exit;
        }

        $goal = $this->goalModel->findById($id);
        if ($goal && $goal['user_id'] == $_SESSION['user_id']) {
            $this->goalModel->delete($id);
            $_SESSION['success'] = 'Goal deleted successfully';
        }

        header('Location: /goals');
        exit;
    }
}
