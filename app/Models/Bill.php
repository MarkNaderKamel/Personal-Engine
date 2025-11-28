<?php

namespace App\Models;

use App\Core\Model;

class Bill extends Model
{
    protected $table = 'bills';

    public function getUpcomingBills($userId, $days = 30)
    {
        $sql = "SELECT * FROM bills 
                WHERE user_id = :user_id 
                AND due_date >= CURRENT_DATE 
                AND due_date <= CURRENT_DATE + INTERVAL '{$days} days'
                AND status != 'paid'
                ORDER BY due_date ASC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getOverdueBills($userId)
    {
        $sql = "SELECT * FROM bills 
                WHERE user_id = :user_id 
                AND due_date < CURRENT_DATE 
                AND status != 'paid'
                ORDER BY due_date ASC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getTotalByCategory($userId)
    {
        $sql = "SELECT category, SUM(amount) as total 
                FROM bills 
                WHERE user_id = :user_id 
                GROUP BY category";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}
