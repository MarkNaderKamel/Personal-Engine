<?php

namespace App\Models;

use App\Core\Model;

class Travel extends Model
{
    protected $table = 'travel_plans';

    public function getUpcoming($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND start_date >= CURRENT_DATE AND status = 'planned' ORDER BY start_date ASC",
            ['user_id' => $userId]
        );
    }

    public function getPast($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND end_date < CURRENT_DATE ORDER BY start_date DESC",
            ['user_id' => $userId]
        );
    }

    public function getTotalBudget($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(budget) as total FROM {$this->table} WHERE user_id = :user_id AND status = 'planned'",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }
}
