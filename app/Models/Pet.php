<?php

namespace App\Models;

use App\Core\Model;

class Pet extends Model
{
    protected $table = 'pets';

    public function getUpcomingCheckups($userId, $days = 30)
    {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND next_checkup IS NOT NULL AND next_checkup <= :end_date ORDER BY next_checkup ASC",
            ['user_id' => $userId, 'end_date' => $endDate]
        );
    }

    public function getUpcomingBirthdays($userId, $days = 30)
    {
        return $this->db->fetchAll(
            "SELECT *, 
             CASE 
                WHEN EXTRACT(DOY FROM birthday) >= EXTRACT(DOY FROM CURRENT_DATE) 
                THEN EXTRACT(DOY FROM birthday) - EXTRACT(DOY FROM CURRENT_DATE)
                ELSE 365 - EXTRACT(DOY FROM CURRENT_DATE) + EXTRACT(DOY FROM birthday)
             END as days_until
             FROM {$this->table} 
             WHERE user_id = :user_id AND birthday IS NOT NULL
             ORDER BY days_until ASC
             LIMIT 5",
            ['user_id' => $userId]
        );
    }
}
