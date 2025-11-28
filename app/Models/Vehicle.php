<?php

namespace App\Models;

use App\Core\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    public function getUpcomingService($userId, $days = 30)
    {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND next_service IS NOT NULL AND next_service <= :end_date ORDER BY next_service ASC",
            ['user_id' => $userId, 'end_date' => $endDate]
        );
    }

    public function getExpiringInsurance($userId, $days = 30)
    {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND insurance_expiry IS NOT NULL AND insurance_expiry <= :end_date ORDER BY insurance_expiry ASC",
            ['user_id' => $userId, 'end_date' => $endDate]
        );
    }
}
