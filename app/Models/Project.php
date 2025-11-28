<?php

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected $table = 'projects';

    public function getActiveProjects($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM projects WHERE user_id = :user_id AND status = 'active' ORDER BY created_at DESC",
            ['user_id' => $userId]
        );
    }

    public function getProjectStats($userId)
    {
        $sql = "SELECT 
                    COUNT(*) FILTER (WHERE status = 'active') as active,
                    COUNT(*) FILTER (WHERE status = 'completed') as completed,
                    COUNT(*) FILTER (WHERE status = 'on_hold') as on_hold
                FROM projects 
                WHERE user_id = :user_id";
        return $this->db->fetchOne($sql, ['user_id' => $userId]);
    }
}
