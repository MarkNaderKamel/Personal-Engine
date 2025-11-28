<?php

namespace App\Models;

use App\Core\Model;

class Habit extends Model
{
    protected $table = 'habits';

    public function findByUserId($userId, $orderBy = 'habit_name', $limit = null)
    {
        $sql = "SELECT h.*, 
             (SELECT COUNT(*) FROM habit_logs WHERE habit_id = h.id AND log_date = CURRENT_DATE) as today_completed,
             (SELECT COUNT(*) FROM habit_logs WHERE habit_id = h.id AND completed_count > 0 
              AND log_date >= CURRENT_DATE - INTERVAL '7 days') as week_count
             FROM {$this->table} h WHERE h.user_id = :user_id AND h.is_active = true ORDER BY h.{$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, habit_name, description, frequency, target_count, category, color, is_active)
                VALUES (:user_id, :habit_name, :description, :frequency, :target_count, :category, :color, :is_active)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET habit_name = :habit_name, description = :description,
                frequency = :frequency, target_count = :target_count, category = :category,
                color = :color, is_active = :is_active WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function toggleActive($id)
    {
        return $this->db->query(
            "UPDATE {$this->table} SET is_active = NOT is_active WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getStats($userId)
    {
        return $this->db->fetchOne(
            "SELECT 
                COUNT(*) as total_habits,
                COUNT(CASE WHEN is_active THEN 1 END) as active_habits,
                (SELECT COUNT(DISTINCT habit_id) FROM habit_logs hl 
                 JOIN habits h ON h.id = hl.habit_id 
                 WHERE h.user_id = :user_id AND log_date = CURRENT_DATE AND completed_count > 0) as completed_today
             FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        ) ?: ['total_habits' => 0, 'active_habits' => 0, 'completed_today' => 0];
    }
}

class HabitLog extends Model
{
    protected $table = 'habit_logs';

    public function findByHabitAndDate($habitId, $date)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE habit_id = :habit_id AND log_date = :log_date",
            ['habit_id' => $habitId, 'log_date' => $date]
        );
    }

    public function logCompletion($habitId, $date, $count = 1, $notes = '')
    {
        $existing = $this->findByHabitAndDate($habitId, $date);
        
        if ($existing) {
            return $this->db->query(
                "UPDATE {$this->table} SET completed_count = completed_count + :count, notes = :notes WHERE id = :id",
                ['id' => $existing['id'], 'count' => $count, 'notes' => $notes]
            );
        } else {
            return $this->db->query(
                "INSERT INTO {$this->table} (habit_id, log_date, completed_count, notes) VALUES (:habit_id, :log_date, :count, :notes)",
                ['habit_id' => $habitId, 'log_date' => $date, 'count' => $count, 'notes' => $notes]
            );
        }
    }

    public function getWeekLogs($habitId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE habit_id = :habit_id AND log_date >= CURRENT_DATE - INTERVAL '7 days'
             ORDER BY log_date DESC",
            ['habit_id' => $habitId]
        );
    }

    public function getMonthLogs($habitId, $month, $year)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE habit_id = :habit_id 
             AND EXTRACT(MONTH FROM log_date) = :month 
             AND EXTRACT(YEAR FROM log_date) = :year
             ORDER BY log_date",
            ['habit_id' => $habitId, 'month' => $month, 'year' => $year]
        );
    }

    public function getStreak($habitId)
    {
        $result = $this->db->fetchAll(
            "SELECT log_date FROM {$this->table} 
             WHERE habit_id = :habit_id AND completed_count > 0
             ORDER BY log_date DESC",
            ['habit_id' => $habitId]
        );
        
        if (empty($result)) return 0;
        
        $streak = 0;
        $expectedDate = date('Y-m-d');
        
        foreach ($result as $log) {
            if ($log['log_date'] === $expectedDate) {
                $streak++;
                $expectedDate = date('Y-m-d', strtotime($expectedDate . ' -1 day'));
            } else {
                break;
            }
        }
        
        return $streak;
    }
}
