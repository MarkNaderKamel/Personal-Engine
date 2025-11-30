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

    public function getTasksByProjectGrouped($projectId)
    {
        $sql = "SELECT * FROM tasks 
                WHERE project_id = :project_id 
                ORDER BY priority DESC, created_at DESC";
        $tasks = $this->db->fetchAll($sql, ['project_id' => $projectId]);
        
        $grouped = [
            'pending' => [],
            'in_progress' => [],
            'review' => [],
            'completed' => []
        ];
        
        foreach ($tasks as $task) {
            $status = $task['status'] ?? 'pending';
            if (!isset($grouped[$status])) {
                $grouped[$status] = [];
            }
            $grouped[$status][] = $task;
        }
        
        return $grouped;
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

    public function updateStatus($taskId, $status, $userId)
    {
        $data = ['status' => $status];
        if ($status === 'completed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        return $this->db->update(
            'tasks',
            $data,
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

    public function getProjectTaskStats($projectId)
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(*) FILTER (WHERE status = 'pending') as pending,
                    COUNT(*) FILTER (WHERE status = 'in_progress') as in_progress,
                    COUNT(*) FILTER (WHERE status = 'review') as review,
                    COUNT(*) FILTER (WHERE status = 'completed') as completed
                FROM tasks 
                WHERE project_id = :project_id";
        return $this->db->fetchOne($sql, ['project_id' => $projectId]);
    }
}
