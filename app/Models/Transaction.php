<?php

namespace App\Models;

use App\Core\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    public function getByDateRange($userId, $startDate, $endDate)
    {
        $sql = "SELECT * FROM transactions 
                WHERE user_id = :user_id 
                AND transaction_date BETWEEN :start_date AND :end_date
                ORDER BY transaction_date DESC, id DESC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    public function getMonthlyStats($userId, $month, $year)
    {
        $sql = "SELECT 
                    SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END) as total_expenses,
                    COUNT(*) as transaction_count
                FROM transactions 
                WHERE user_id = :user_id 
                AND EXTRACT(MONTH FROM transaction_date) = :month
                AND EXTRACT(YEAR FROM transaction_date) = :year";
        return $this->db->fetchOne($sql, [
            'user_id' => $userId,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function getExpensesByCategory($userId, $month, $year)
    {
        $sql = "SELECT category, SUM(amount) as total
                FROM transactions 
                WHERE user_id = :user_id 
                AND transaction_type = 'expense'
                AND EXTRACT(MONTH FROM transaction_date) = :month
                AND EXTRACT(YEAR FROM transaction_date) = :year
                GROUP BY category
                ORDER BY total DESC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function getIncomeByCategory($userId, $month, $year)
    {
        $sql = "SELECT category, SUM(amount) as total
                FROM transactions 
                WHERE user_id = :user_id 
                AND transaction_type = 'income'
                AND EXTRACT(MONTH FROM transaction_date) = :month
                AND EXTRACT(YEAR FROM transaction_date) = :year
                GROUP BY category
                ORDER BY total DESC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'month' => $month,
            'year' => $year
        ]);
    }

    public function getRecentTransactions($userId, $limit = 10)
    {
        $sql = "SELECT * FROM transactions 
                WHERE user_id = :user_id 
                ORDER BY transaction_date DESC, id DESC
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['user_id' => $userId, 'limit' => $limit]);
    }

    public function getYearlyStats($userId, $year)
    {
        $sql = "SELECT 
                    EXTRACT(MONTH FROM transaction_date) as month,
                    SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) as income,
                    SUM(CASE WHEN transaction_type = 'expense' THEN amount ELSE 0 END) as expenses
                FROM transactions 
                WHERE user_id = :user_id 
                AND EXTRACT(YEAR FROM transaction_date) = :year
                GROUP BY EXTRACT(MONTH FROM transaction_date)
                ORDER BY month";
        return $this->db->fetchAll($sql, ['user_id' => $userId, 'year' => $year]);
    }

    public function getBalance($userId)
    {
        $sql = "SELECT 
                    SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE -amount END) as balance
                FROM transactions 
                WHERE user_id = :user_id";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result ? $result['balance'] : 0;
    }
}
