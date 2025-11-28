<?php

namespace App\Controllers;

use App\Core\Security;
use App\Core\Database;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Gamification;
use App\Models\Budget;
use App\Models\Subscription;

class AnalyticsController
{
    private $db;
    private $billModel;
    private $taskModel;
    private $gamification;
    private $budgetModel;
    private $subscriptionModel;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->billModel = new Bill();
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
        $this->budgetModel = new Budget();
        $this->subscriptionModel = new Subscription();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $data = [
            'financialSummary' => $this->getFinancialSummary($userId),
            'productivityStats' => $this->getProductivityStats($userId),
            'monthlyBills' => $this->getMonthlyBillsChart($userId),
            'taskCompletion' => $this->getTaskCompletionRate($userId),
            'xpProgress' => $this->getXPProgress($userId),
            'categoryBreakdown' => $this->getCategoryBreakdown($userId),
            'recentActivity' => $this->getRecentActivity($userId)
        ];
        
        require __DIR__ . '/../Views/modules/analytics/index.php';
    }

    private function getFinancialSummary($userId)
    {
        $billsTotal = $this->db->fetchOne(
            "SELECT COALESCE(SUM(amount), 0) as total FROM bills WHERE user_id = :user_id AND status = 'pending'",
            ['user_id' => $userId]
        );

        $budgetSpent = $this->db->fetchOne(
            "SELECT COALESCE(SUM(spent_amount), 0) as spent, COALESCE(SUM(budgeted_amount), 0) as budgeted 
             FROM budgets WHERE user_id = :user_id AND month = :month AND year = :year",
            ['user_id' => $userId, 'month' => date('n'), 'year' => date('Y')]
        );

        $subscriptionTotal = $this->subscriptionModel->getMonthlyTotal($userId);

        return [
            'pending_bills' => $billsTotal['total'] ?? 0,
            'budget_spent' => $budgetSpent['spent'] ?? 0,
            'budget_total' => $budgetSpent['budgeted'] ?? 0,
            'subscriptions' => $subscriptionTotal
        ];
    }

    private function getProductivityStats($userId)
    {
        $taskStats = $this->taskModel->getTaskStats($userId);
        
        $projectCount = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM projects WHERE user_id = :user_id AND status = 'active'",
            ['user_id' => $userId]
        );

        $noteCount = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM notes WHERE user_id = :user_id",
            ['user_id' => $userId]
        );

        $eventCount = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM events WHERE user_id = :user_id AND event_date >= CURRENT_DATE",
            ['user_id' => $userId]
        );

        return [
            'tasks_pending' => $taskStats['pending'] ?? 0,
            'tasks_completed' => $taskStats['completed'] ?? 0,
            'active_projects' => $projectCount['count'] ?? 0,
            'notes' => $noteCount['count'] ?? 0,
            'upcoming_events' => $eventCount['count'] ?? 0
        ];
    }

    private function getMonthlyBillsChart($userId)
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $result = $this->db->fetchOne(
                "SELECT COALESCE(SUM(amount), 0) as total FROM bills 
                 WHERE user_id = :user_id 
                 AND TO_CHAR(due_date, 'YYYY-MM') = :month",
                ['user_id' => $userId, 'month' => $month]
            );
            $data[] = [
                'month' => date('M Y', strtotime("-$i months")),
                'total' => floatval($result['total'] ?? 0)
            ];
        }
        return $data;
    }

    private function getTaskCompletionRate($userId)
    {
        $last30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $completed = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM tasks 
                 WHERE user_id = :user_id 
                 AND DATE(completed_at) = :date",
                ['user_id' => $userId, 'date' => $date]
            );
            $last30Days[] = [
                'date' => date('M d', strtotime("-$i days")),
                'completed' => intval($completed['count'] ?? 0)
            ];
        }
        return $last30Days;
    }

    private function getXPProgress($userId)
    {
        $stats = $this->gamification->getUserStats($userId);
        $transactions = $this->gamification->getRecentTransactions($userId, 30);
        
        return [
            'total_xp' => $stats['total_xp'] ?? 0,
            'level' => $stats['level'] ?? 1,
            'current_streak' => $stats['current_streak'] ?? 0,
            'longest_streak' => $stats['longest_streak'] ?? 0,
            'xp_to_next_level' => 1000 - (($stats['total_xp'] ?? 0) % 1000),
            'recent_transactions' => $transactions
        ];
    }

    private function getCategoryBreakdown($userId)
    {
        $billCategories = $this->db->fetchAll(
            "SELECT category, SUM(amount) as total FROM bills 
             WHERE user_id = :user_id AND category IS NOT NULL 
             GROUP BY category ORDER BY total DESC LIMIT 5",
            ['user_id' => $userId]
        );

        $budgetCategories = $this->db->fetchAll(
            "SELECT category, SUM(budgeted_amount) as budgeted, SUM(spent_amount) as spent 
             FROM budgets WHERE user_id = :user_id 
             AND month = :month AND year = :year
             GROUP BY category ORDER BY budgeted DESC",
            ['user_id' => $userId, 'month' => date('n'), 'year' => date('Y')]
        );

        return [
            'bills' => $billCategories,
            'budgets' => $budgetCategories
        ];
    }

    private function getRecentActivity($userId)
    {
        return $this->db->fetchAll(
            "SELECT action_type, xp_earned, description, created_at 
             FROM xp_transactions 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC LIMIT 10",
            ['user_id' => $userId]
        );
    }
}
