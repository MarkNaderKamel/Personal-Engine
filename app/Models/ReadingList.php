<?php

namespace App\Models;

use App\Core\Model;

class ReadingList extends Model
{
    protected $table = 'reading_list';

    public function getByStatus($userId, $status)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = :status ORDER BY created_at DESC",
            ['user_id' => $userId, 'status' => $status]
        );
    }

    public function getCurrentlyReading($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = 'reading' ORDER BY started_at DESC",
            ['user_id' => $userId]
        );
    }

    public function getStats($userId)
    {
        return $this->db->fetchOne(
            "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'to_read' THEN 1 END) as to_read,
                COUNT(CASE WHEN status = 'reading' THEN 1 END) as reading,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                AVG(CASE WHEN rating IS NOT NULL THEN rating END) as avg_rating
             FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
    }
}
