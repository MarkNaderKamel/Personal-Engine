<?php

namespace App\Models;

use App\Core\Model;

class Relationship extends Model
{
    protected $table = 'relationships';

    public function getAllByUser($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY person_name ASC",
            ['user_id' => $userId]
        );
    }

    public function findByIdAndUser($id, $userId)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id",
            ['id' => $id, 'user_id' => $userId]
        );
    }

    public function updateByUser($id, $userId, $data)
    {
        return $this->db->update($this->table, $data, 'id = :id AND user_id = :user_id', ['id' => $id, 'user_id' => $userId]);
    }

    public function deleteByUser($id, $userId)
    {
        return $this->db->delete($this->table, 'id = :id AND user_id = :user_id', ['id' => $id, 'user_id' => $userId]);
    }

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
