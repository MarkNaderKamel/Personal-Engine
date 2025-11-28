<?php

namespace App\Models;

use App\Core\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public function getPendingTasks($userId)
    {
        $sql = "SELECT * FROM tasks 
                WHERE user_id = :user_id 
                AND status != 'completed'
                ORDER BY due_date ASC, priority DESC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getTasksByProject($projectId)
    {
        $sql = "SELECT * FROM tasks 
                WHERE project_id = :project_id 
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['project_id' => $projectId]);
    }

    public function markComplete($taskId, $userId)
    {
        return $this->db->update(
            'tasks',
            ['status' => 'completed', 'completed_at' => date('Y-m-d H:i:s')],
            'id = :id AND user_id = :user_id',
            ['id' => $taskId, 'user_id' => $userId]
        );
    }

    public function getTaskStats($userId)
    {
        $sql = "SELECT 
                    COUNT(*) FILTER (WHERE status = 'pending') as pending,
                    COUNT(*) FILTER (WHERE status = 'in_progress') as in_progress,
                    COUNT(*) FILTER (WHERE status = 'completed') as completed
                FROM tasks 
                WHERE user_id = :user_id";
        return $this->db->fetchOne($sql, ['user_id' => $userId]);
    }
}
