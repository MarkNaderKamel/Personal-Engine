<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Bill;
use App\Models\Task;
use App\Models\Gamification;
use App\Models\Notification;
use App\Models\Budget;
use App\Models\Subscription;
use App\Models\JobApplication;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\Birthday;

class DashboardController
{
    private $billModel;
    private $taskModel;
    private $gamification;
    private $notificationModel;
    private $budgetModel;
    private $subscriptionModel;
    private $jobModel;
    private $goalModel;
    private $habitModel;
    private $birthdayModel;

    public function __construct()
    {
        $this->billModel = new Bill();
        $this->taskModel = new Task();
        $this->gamification = new Gamification();
        $this->notificationModel = new Notification();
        $this->budgetModel = new Budget();
        $this->subscriptionModel = new Subscription();
        $this->jobModel = new JobApplication();
        $this->goalModel = new Goal();
        $this->habitModel = new Habit();
        $this->birthdayModel = new Birthday();
    }

    public function index()
    {
        Security::requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        $data = [
            'upcomingBills' => $this->billModel->getUpcomingBills($userId, 7) ?: [],
            'overdueBills' => $this->billModel->getOverdueBills($userId) ?: [],
            'pendingTasks' => $this->taskModel->getPendingTasks($userId) ?: [],
            'taskStats' => $this->taskModel->getTaskStats($userId) ?: ['pending' => 0, 'completed' => 0],
            'userStats' => $this->gamification->getUserStats($userId),
            'recentXP' => $this->gamification->getRecentTransactions($userId, 5) ?: [],
            'unreadNotifications' => $this->notificationModel->getUnreadNotifications($userId) ?: [],
            'currentBudgets' => $this->budgetModel->getCurrentMonthBudgets($userId) ?: [],
            'activeSubscriptions' => $this->subscriptionModel->getActiveSubscriptions($userId) ?: [],
            'monthlySubscriptionTotal' => $this->subscriptionModel->getMonthlyTotal($userId) ?: 0,
            'jobStats' => $this->jobModel->getStats($userId),
            'activeGoals' => $this->goalModel->getActiveGoals($userId) ?: [],
            'goalStats' => $this->goalModel->getStats($userId),
            'habits' => $this->habitModel->findByUserId($userId) ?: [],
            'habitStats' => $this->habitModel->getStats($userId),
            'upcomingBirthdays' => $this->birthdayModel->getUpcoming($userId, 10) ?: [],
            'todayBirthdays' => $this->birthdayModel->getTodayBirthdays($userId) ?: []
        ];
        
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}
