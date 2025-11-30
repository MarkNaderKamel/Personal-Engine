<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\WellnessLog;
use App\Models\Gamification;

class WellnessController
{
    private $wellnessModel;
    private $gamification;

    public function __construct()
    {
        $this->wellnessModel = new WellnessLog();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $logs = $this->wellnessModel->getLastNDays($userId, 30);
        $averages = $this->wellnessModel->getAverages($userId, 30);
        $todayLog = $this->wellnessModel->getTodayLog($userId);
        $streak = $this->wellnessModel->getStreak($userId);
        $weeklyTrends = $this->wellnessModel->getWeeklyTrends($userId);
        
        require __DIR__ . '/../Views/modules/wellness/index.php';
    }

    public function log()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $todayLog = $this->wellnessModel->getTodayLog($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /wellness');
                exit;
            }

            $data = [
                'user_id' => $userId,
                'water_intake' => floatval($_POST['water_intake'] ?? 0),
                'sleep_hours' => floatval($_POST['sleep_hours'] ?? 0),
                'mood_score' => !empty($_POST['mood_score']) ? intval($_POST['mood_score']) : null,
                'energy_level' => !empty($_POST['energy_level']) ? intval($_POST['energy_level']) : null,
                'stress_level' => !empty($_POST['stress_level']) ? intval($_POST['stress_level']) : null,
                'exercise_minutes' => intval($_POST['exercise_minutes'] ?? 0),
                'steps_count' => intval($_POST['steps_count'] ?? 0),
                'weight' => !empty($_POST['weight']) ? floatval($_POST['weight']) : null,
                'calories_consumed' => !empty($_POST['calories_consumed']) ? intval($_POST['calories_consumed']) : null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'recorded_at' => $_POST['recorded_at'] ?? date('Y-m-d')
            ];

            if ($this->wellnessModel->create($data)) {
                if (!$todayLog) {
                    $this->gamification->addXP($userId, 15, 'wellness_logged', 'Logged wellness data');
                }
                $_SESSION['success'] = 'Wellness data logged successfully';
            } else {
                $_SESSION['error'] = 'Failed to log wellness data';
            }

            header('Location: /wellness');
            exit;
        }

        require __DIR__ . '/../Views/modules/wellness/log.php';
    }

    public function history()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $logs = $this->wellnessModel->findByUserId($userId);
        
        require __DIR__ . '/../Views/modules/wellness/history.php';
    }

    public function getChartData()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $days = isset($_GET['days']) ? intval($_GET['days']) : 30;
        $logs = $this->wellnessModel->getLastNDays($userId, $days);
        
        $chartData = [
            'labels' => [],
            'mood' => [],
            'energy' => [],
            'sleep' => [],
            'water' => [],
            'exercise' => [],
            'stress' => []
        ];
        
        foreach ($logs as $log) {
            $chartData['labels'][] = date('M j', strtotime($log['recorded_at']));
            $chartData['mood'][] = $log['mood_score'];
            $chartData['energy'][] = $log['energy_level'];
            $chartData['sleep'][] = $log['sleep_hours'];
            $chartData['water'][] = $log['water_intake'];
            $chartData['exercise'][] = $log['exercise_minutes'];
            $chartData['stress'][] = $log['stress_level'];
        }
        
        header('Content-Type: application/json');
        echo json_encode($chartData);
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /wellness');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /wellness');
            exit;
        }

        $log = $this->wellnessModel->findById($id);
        
        if (!$log || $log['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Log not found';
            header('Location: /wellness');
            exit;
        }

        if ($this->wellnessModel->delete($id)) {
            $_SESSION['success'] = 'Wellness log deleted';
        } else {
            $_SESSION['error'] = 'Failed to delete log';
        }

        header('Location: /wellness');
        exit;
    }
}
