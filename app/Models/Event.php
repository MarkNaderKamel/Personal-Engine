<?php

namespace App\Models;

use App\Core\Model;

class Event extends Model
{
    protected $table = 'events';

    public function getUpcoming($userId, $days = 7)
    {
        $endDate = date('Y-m-d', strtotime("+{$days} days"));
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND event_date >= CURRENT_DATE AND event_date <= :end_date ORDER BY event_date ASC, event_time ASC",
            ['user_id' => $userId, 'end_date' => $endDate]
        );
    }

    public function getByMonth($userId, $month, $year)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND EXTRACT(MONTH FROM event_date) = :month AND EXTRACT(YEAR FROM event_date) = :year ORDER BY event_date ASC",
            ['user_id' => $userId, 'month' => $month, 'year' => $year]
        );
    }

    public function getTodayEvents($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND event_date = CURRENT_DATE ORDER BY event_time ASC",
            ['user_id' => $userId]
        );
    }
}
