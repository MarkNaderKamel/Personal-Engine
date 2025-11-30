<?php

namespace App\Controllers;

use App\Core\Security;
use App\Core\Database;

class ForecastController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $currentBalance = $this->getCurrentBalance($userId);
        $monthlyIncome = $this->getAverageMonthlyIncome($userId);
        $monthlyExpenses = $this->getAverageMonthlyExpenses($userId);
        $upcomingBills = $this->getUpcomingBills($userId);
        $subscriptionTotal = $this->getSubscriptionTotal($userId);
        $debtPayments = $this->getMonthlyDebtPayments($userId);
        $savingsRate = $this->getSavingsRate($userId);
        $expensesByCategory = $this->getExpensesByCategory($userId);
        $incomeHistory = $this->getIncomeHistory($userId);
        $forecastData = $this->generateForecast($userId, 12);
        
        require __DIR__ . '/../Views/modules/forecast/index.php';
    }

    private function getCurrentBalance($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COALESCE(SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END), 0) -
                COALESCE(SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END), 0) as balance
            FROM transactions 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['balance'] ?? 0;
    }

    private function getAverageMonthlyIncome($userId)
    {
        $stmt = $this->db->prepare("
            SELECT AVG(monthly_income) as avg_income FROM (
                SELECT DATE_TRUNC('month', transaction_date) as month,
                       SUM(amount) as monthly_income
                FROM transactions
                WHERE user_id = ? AND transaction_type = 'income'
                AND transaction_date >= CURRENT_DATE - INTERVAL '6 months'
                GROUP BY DATE_TRUNC('month', transaction_date)
            ) monthly
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['avg_income'] ?? 0;
    }

    private function getAverageMonthlyExpenses($userId)
    {
        $stmt = $this->db->prepare("
            SELECT AVG(monthly_expense) as avg_expense FROM (
                SELECT DATE_TRUNC('month', transaction_date) as month,
                       SUM(amount) as monthly_expense
                FROM transactions
                WHERE user_id = ? AND transaction_type = 'expense'
                AND transaction_date >= CURRENT_DATE - INTERVAL '6 months'
                GROUP BY DATE_TRUNC('month', transaction_date)
            ) monthly
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['avg_expense'] ?? 0;
    }

    private function getUpcomingBills($userId)
    {
        $stmt = $this->db->prepare("
            SELECT bill_name, amount, due_date, is_recurring
            FROM bills
            WHERE user_id = ? 
            AND status != 'paid'
            AND due_date >= CURRENT_DATE
            AND due_date <= CURRENT_DATE + INTERVAL '30 days'
            ORDER BY due_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    private function getSubscriptionTotal($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(
                CASE 
                    WHEN billing_cycle = 'weekly' THEN amount * 4.33
                    WHEN billing_cycle = 'yearly' THEN amount / 12
                    ELSE amount 
                END
            ), 0) as monthly_total
            FROM subscriptions
            WHERE user_id = ? AND is_active = true
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['monthly_total'] ?? 0;
    }

    private function getMonthlyDebtPayments($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(minimum_payment), 0) as total_payments
            FROM debts
            WHERE user_id = ? AND is_paid_off = false
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch()['total_payments'] ?? 0;
    }

    private function getSavingsRate($userId)
    {
        $income = $this->getAverageMonthlyIncome($userId);
        $expenses = $this->getAverageMonthlyExpenses($userId);
        
        if ($income <= 0) return 0;
        
        return (($income - $expenses) / $income) * 100;
    }

    private function getExpensesByCategory($userId)
    {
        $stmt = $this->db->prepare("
            SELECT category, SUM(amount) as total
            FROM transactions
            WHERE user_id = ? 
            AND transaction_type = 'expense'
            AND transaction_date >= CURRENT_DATE - INTERVAL '3 months'
            GROUP BY category
            ORDER BY total DESC
            LIMIT 10
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    private function getIncomeHistory($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                TO_CHAR(DATE_TRUNC('month', transaction_date), 'Mon YYYY') as month,
                SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END) as expense
            FROM transactions
            WHERE user_id = ?
            AND transaction_date >= CURRENT_DATE - INTERVAL '12 months'
            GROUP BY DATE_TRUNC('month', transaction_date)
            ORDER BY DATE_TRUNC('month', transaction_date) ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    private function generateForecast($userId, $months)
    {
        $currentBalance = $this->getCurrentBalance($userId);
        $avgIncome = $this->getAverageMonthlyIncome($userId);
        $avgExpense = $this->getAverageMonthlyExpenses($userId);
        $netMonthly = $avgIncome - $avgExpense;
        
        $forecast = [];
        $balance = $currentBalance;
        
        for ($i = 1; $i <= $months; $i++) {
            $date = date('M Y', strtotime("+{$i} months"));
            $balance += $netMonthly;
            $forecast[] = [
                'month' => $date,
                'projected_balance' => $balance,
                'projected_income' => $avgIncome,
                'projected_expense' => $avgExpense,
                'net' => $netMonthly
            ];
        }
        
        return $forecast;
    }

    public function getScenarios()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $currentBalance = $this->getCurrentBalance($userId);
        $avgIncome = $this->getAverageMonthlyIncome($userId);
        $avgExpense = $this->getAverageMonthlyExpenses($userId);
        
        $scenarios = [
            'optimistic' => $this->generateScenario($currentBalance, $avgIncome * 1.1, $avgExpense * 0.9, 12),
            'base' => $this->generateScenario($currentBalance, $avgIncome, $avgExpense, 12),
            'conservative' => $this->generateScenario($currentBalance, $avgIncome * 0.9, $avgExpense * 1.1, 12)
        ];
        
        header('Content-Type: application/json');
        echo json_encode($scenarios);
        exit;
    }

    private function generateScenario($startBalance, $income, $expense, $months)
    {
        $data = [];
        $balance = $startBalance;
        
        for ($i = 1; $i <= $months; $i++) {
            $balance += ($income - $expense);
            $data[] = round($balance, 2);
        }
        
        return $data;
    }

    public function goalProjection()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $targetAmount = floatval($_GET['target'] ?? 10000);
        $currentBalance = $this->getCurrentBalance($userId);
        $avgIncome = $this->getAverageMonthlyIncome($userId);
        $avgExpense = $this->getAverageMonthlyExpenses($userId);
        $monthlySavings = $avgIncome - $avgExpense;
        
        if ($monthlySavings <= 0) {
            $monthsToGoal = -1;
        } else {
            $remaining = $targetAmount - $currentBalance;
            $monthsToGoal = $remaining <= 0 ? 0 : ceil($remaining / $monthlySavings);
        }
        
        $projection = [
            'target' => $targetAmount,
            'current_balance' => $currentBalance,
            'monthly_savings' => $monthlySavings,
            'months_to_goal' => $monthsToGoal,
            'projected_date' => $monthsToGoal > 0 ? date('F Y', strtotime("+{$monthsToGoal} months")) : 'Already reached!'
        ];
        
        header('Content-Type: application/json');
        echo json_encode($projection);
        exit;
    }
}
