<?php

namespace App\Models;

use App\Core\Model;

class Debt extends Model
{
    protected $table = 'debts';

    public function getTotalDebt($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(current_balance) as total FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }

    public function getMonthlyPayments($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(minimum_payment) as total FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }
}
