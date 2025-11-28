<?php

namespace App\Models;

use App\Core\Model;

class Goal extends Model
{
    protected $table = 'goals';

    public function findByUserId($userId, $orderBy = 'target_date ASC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
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
        $sql = "INSERT INTO {$this->table} (user_id, goal_title, description, category, target_date, start_date, status, priority, progress)
                VALUES (:user_id, :goal_title, :description, :category, :target_date, :start_date, :status, :priority, :progress)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                goal_title = :goal_title, description = :description, category = :category,
                target_date = :target_date, start_date = :start_date, status = :status,
                priority = :priority, progress = :progress, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function updateProgress($id, $progress)
    {
        $status = $progress >= 100 ? 'completed' : 'in_progress';
        return $this->db->query(
            "UPDATE {$this->table} SET progress = :progress, status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id",
            ['id' => $id, 'progress' => min(100, max(0, $progress)), 'status' => $status]
        );
    }

    public function getActiveGoals($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = 'in_progress' ORDER BY target_date ASC",
            ['user_id' => $userId]
        );
    }

    public function getByCategory($userId, $category)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND category = :category ORDER BY target_date ASC",
            ['user_id' => $userId, 'category' => $category]
        );
    }

    public function getStats($userId)
    {
        return $this->db->fetchOne(
            "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as active,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                COUNT(CASE WHEN status = 'paused' THEN 1 END) as paused,
                AVG(CASE WHEN status = 'in_progress' THEN progress END) as avg_progress
             FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        ) ?: ['total' => 0, 'active' => 0, 'completed' => 0, 'paused' => 0, 'avg_progress' => 0];
    }

    public function getUpcoming($userId, $days = 30)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE user_id = :user_id 
             AND status = 'in_progress'
             AND target_date <= CURRENT_DATE + INTERVAL '{$days} days'
             ORDER BY target_date ASC",
            ['user_id' => $userId]
        );
    }
}

class GoalMilestone extends Model
{
    protected $table = 'goal_milestones';

    public function findByGoalId($goalId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE goal_id = :goal_id ORDER BY target_date ASC",
            ['goal_id' => $goalId]
        );
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (goal_id, milestone_title, description, target_date, is_completed)
                VALUES (:goal_id, :milestone_title, :description, :target_date, :is_completed)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET milestone_title = :milestone_title, description = :description,
                target_date = :target_date WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function markComplete($id)
    {
        return $this->db->query(
            "UPDATE {$this->table} SET is_completed = true, completed_at = CURRENT_TIMESTAMP WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getCompletedCount($goalId)
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE goal_id = :goal_id AND is_completed = true",
            ['goal_id' => $goalId]
        );
        return $result['count'] ?? 0;
    }

    public function getTotalCount($goalId)
    {
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE goal_id = :goal_id",
            ['goal_id' => $goalId]
        );
        return $result['count'] ?? 0;
    }
}
