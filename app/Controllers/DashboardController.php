<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Gamification;
use App\Models\Notification;
use App\Models\Budget;
use App\Models\Subscription;

class DashboardController
{
    private $billModel;
    private $taskModel;
    private $gamification;
    private $notificationModel;
    private $budgetModel;
    private $subscriptionModel;

    public function __construct()
    {
        $this->billModel = new Bill();
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
        $this->notificationModel = new Notification();
        $this->budgetModel = new Budget();
        $this->subscriptionModel = new Subscription();
    }

    public function index()
    {
        Security::requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        $data = [
            'upcomingBills' => $this->billModel->getUpcomingBills($userId, 7),
            'overdueBills' => $this->billModel->getOverdueBills($userId),
            'pendingTasks' => $this->taskModel->getPendingTasks($userId),
            'taskStats' => $this->taskModel->getTaskStats($userId),
            'userStats' => $this->gamification->getUserStats($userId),
            'recentXP' => $this->gamification->getRecentTransactions($userId, 5),
            'unreadNotifications' => $this->notificationModel->getUnreadNotifications($userId),
            'currentBudgets' => $this->budgetModel->getCurrentMonthBudgets($userId),
            'activeSubscriptions' => $this->subscriptionModel->getActiveSubscriptions($userId),
            'monthlySubscriptionTotal' => $this->subscriptionModel->getMonthlyTotal($userId)
        ];
        
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}
