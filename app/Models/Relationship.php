<?php

namespace App\Models;

use App\Core\Model;

class Relationship extends Model
{
    protected $table = 'relationships';

    public function getByType($userId, $type)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND relationship_type = :type ORDER BY person_name ASC",
            ['user_id' => $userId, 'type' => $type]
        );
    }

    public function getUpcomingDates($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND important_dates IS NOT NULL ORDER BY person_name ASC",
            ['user_id' => $userId]
        );
    }
}
