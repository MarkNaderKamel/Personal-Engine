<?php

namespace App\Models;

use App\Core\Model;

class TimeTracking extends Model
{
    protected $table = 'time_tracking';

    public function getActiveTimer($userId)
    {
        return $this->db->fetchOne(
            "SELECT tt.*, t.title as task_title 
             FROM {$this->table} tt 
             LEFT JOIN tasks t ON tt.task_id = t.id 
             WHERE tt.user_id = :user_id AND tt.end_time IS NULL 
             ORDER BY tt.start_time DESC LIMIT 1",
            ['user_id' => $userId]
        );
    }

    public function startTimer($userId, $taskId = null, $notes = '')
    {
        $activeTimer = $this->getActiveTimer($userId);
        if ($activeTimer) {
            $this->stopTimer($activeTimer['id'], $userId);
        }

        return $this->create([
            'user_id' => $userId,
            'task_id' => $taskId,
            'start_time' => date('Y-m-d H:i:s'),
            'notes' => $notes
        ]);
    }

    public function stopTimer($id, $userId)
    {
        $timer = $this->findById($id);
        if (!$timer || $timer['user_id'] != $userId) {
            return false;
        }

        $endTime = date('Y-m-d H:i:s');
        $startTime = strtotime($timer['start_time']);
        $duration = time() - $startTime;

        return $this->db->update(
            $this->table,
            [
                'end_time' => $endTime,
                'duration' => $duration
            ],
            'id = :id',
            ['id' => $id]
        );
    }

    public function getTodayTotal($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(duration) as total FROM {$this->table} 
             WHERE user_id = :user_id AND DATE(start_time) = CURRENT_DATE AND duration IS NOT NULL",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }

    public function getWeekTotal($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(duration) as total FROM {$this->table} 
             WHERE user_id = :user_id AND start_time >= CURRENT_DATE - INTERVAL '7 days' AND duration IS NOT NULL",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }

    public function getRecentEntries($userId, $limit = 10)
    {
        return $this->db->fetchAll(
            "SELECT tt.*, t.title as task_title 
             FROM {$this->table} tt 
             LEFT JOIN tasks t ON tt.task_id = t.id 
             WHERE tt.user_id = :user_id AND tt.duration IS NOT NULL
             ORDER BY tt.start_time DESC LIMIT :limit",
            ['user_id' => $userId, 'limit' => $limit]
        );
    }
}
