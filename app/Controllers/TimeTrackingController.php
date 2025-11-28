<?php

namespace App\Controllers;

use App\Models\TimeTracking;
use App\Models\Task;
use App\Models\Gamification;
use App\Core\Security;

class TimeTrackingController
{
    private $timeModel;
    private $taskModel;
    private $gamification;

    public function __construct()
    {
        $this->timeModel = new TimeTracking();
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $entries = $this->timeModel->getRecentEntries($_SESSION['user_id'], 20);
        $activeTimer = $this->timeModel->getActiveTimer($_SESSION['user_id']);
        $todayTotal = $this->timeModel->getTodayTotal($_SESSION['user_id']);
        $weekTotal = $this->timeModel->getWeekTotal($_SESSION['user_id']);
        $tasks = $this->taskModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/time-tracking/index.php';
    }

    public function start()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /time-tracking');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /time-tracking');
            exit;
        }

        $taskId = !empty($_POST['task_id']) ? intval($_POST['task_id']) : null;
        $notes = Security::sanitizeInput($_POST['notes'] ?? '');

        if ($this->timeModel->startTimer($_SESSION['user_id'], $taskId, $notes)) {
            $_SESSION['success'] = 'Timer started';
        } else {
            $_SESSION['error'] = 'Failed to start timer';
        }

        header('Location: /time-tracking');
        exit;
    }

    public function stop($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /time-tracking');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /time-tracking');
            exit;
        }

        if ($this->timeModel->stopTimer($id, $_SESSION['user_id'])) {
            $this->gamification->addXP($_SESSION['user_id'], 10, 'time_tracked', 'Tracked time');
            $_SESSION['success'] = 'Timer stopped';
        } else {
            $_SESSION['error'] = 'Failed to stop timer';
        }

        header('Location: /time-tracking');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /time-tracking');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /time-tracking');
            exit;
        }

        $entry = $this->timeModel->findById($id);
        
        if ($entry && $entry['user_id'] == $_SESSION['user_id']) {
            $this->timeModel->delete($id);
            $_SESSION['success'] = 'Entry deleted';
        }

        header('Location: /time-tracking');
        exit;
    }

    public function getActive()
    {
        Security::requireAuth();
        header('Content-Type: application/json');
        
        $activeTimer = $this->timeModel->getActiveTimer($_SESSION['user_id']);
        
        if ($activeTimer) {
            $elapsed = time() - strtotime($activeTimer['start_time']);
            echo json_encode([
                'active' => true,
                'id' => $activeTimer['id'],
                'elapsed' => $elapsed,
                'task_title' => $activeTimer['task_title'] ?? 'No task'
            ]);
        } else {
            echo json_encode(['active' => false]);
        }
        exit;
    }
}
