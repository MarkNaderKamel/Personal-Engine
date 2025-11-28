<?php

namespace App\Models;

use App\Core\Model;

class Budget extends Model
{
    protected $table = 'budgets';

    public function getCurrentMonthBudgets($userId)
    {
        $month = date('n');
        $year = date('Y');
        
        $sql = "SELECT * FROM budgets 
                WHERE user_id = :user_id 
                AND month = :month 
                AND year = :year";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function updateSpent($budgetId, $amount)
    {
        $sql = "UPDATE budgets 
                SET spent_amount = spent_amount + :amount 
                WHERE id = :id";
        return $this->db->query($sql, ['amount' => $amount, 'id' => $budgetId]);
    }

    public function getBudgetSummary($userId, $month, $year)
    {
        $sql = "SELECT 
                    SUM(budgeted_amount) as total_budgeted,
                    SUM(spent_amount) as total_spent
                FROM budgets 
                WHERE user_id = :user_id AND month = :month AND year = :year";
        return $this->db->fetchOne($sql, [
            'user_id' => $userId,
            'month' => $month,
            'year' => $year
        ]);
    }
}
