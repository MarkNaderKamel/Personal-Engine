<?php

namespace App\Models;

use App\Core\Model;

class Contract extends Model
{
    protected $table = 'contracts';

    public function getActive($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = 'active' ORDER BY end_date ASC",
            ['user_id' => $userId]
        );
    }

    public function getExpiring($userId, $days = 30)
    {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = 'active' AND end_date <= :end_date AND end_date >= CURRENT_DATE ORDER BY end_date ASC",
            ['user_id' => $userId, 'end_date' => $endDate]
        );
    }

    public function getTotalValue($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(value) as total FROM {$this->table} WHERE user_id = :user_id AND status = 'active'",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }
}
