<?php

namespace App\Models;

use App\Core\Model;

class WellnessLog extends Model
{
    protected $table = 'wellness_logs';

    public function findByUserId($userId, $orderBy = 'recorded_at DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findByDate($userId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND recorded_at = ?
        ");
        $stmt->execute([$userId, $date]);
        return $stmt->fetch();
    }

    public function getLastNDays($userId, $days = 30)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND recorded_at >= CURRENT_DATE - INTERVAL '{$days} days'
            ORDER BY recorded_at ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAverages($userId, $days = 30)
    {
        $stmt = $this->db->prepare("
            SELECT 
                AVG(water_intake) as avg_water,
                AVG(sleep_hours) as avg_sleep,
                AVG(mood_score) as avg_mood,
                AVG(energy_level) as avg_energy,
                AVG(stress_level) as avg_stress,
                AVG(exercise_minutes) as avg_exercise,
                AVG(steps_count) as avg_steps,
                AVG(weight) as avg_weight,
                AVG(calories_consumed) as avg_calories,
                COUNT(*) as total_logs
            FROM {$this->table} 
            WHERE user_id = ? 
            AND recorded_at >= CURRENT_DATE - INTERVAL '{$days} days'
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getWeeklyTrends($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                DATE_TRUNC('week', recorded_at) as week_start,
                AVG(mood_score) as avg_mood,
                AVG(energy_level) as avg_energy,
                AVG(sleep_hours) as avg_sleep,
                SUM(exercise_minutes) as total_exercise,
                COUNT(*) as entries
            FROM {$this->table}
            WHERE user_id = ? 
            AND recorded_at >= CURRENT_DATE - INTERVAL '12 weeks'
            GROUP BY DATE_TRUNC('week', recorded_at)
            ORDER BY week_start ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getTodayLog($userId)
    {
        return $this->findByDate($userId, date('Y-m-d'));
    }

    public function getStreak($userId)
    {
        $stmt = $this->db->prepare("
            WITH dates AS (
                SELECT DISTINCT recorded_at 
                FROM {$this->table} 
                WHERE user_id = ?
                ORDER BY recorded_at DESC
            ),
            streaks AS (
                SELECT recorded_at, 
                    recorded_at - (ROW_NUMBER() OVER (ORDER BY recorded_at DESC))::integer as grp
                FROM dates
            )
            SELECT COUNT(*) as streak
            FROM streaks
            WHERE grp = (SELECT grp FROM streaks ORDER BY recorded_at DESC LIMIT 1)
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ? $result['streak'] : 0;
    }

    public function create($data)
    {
        $existing = $this->findByDate($data['user_id'], $data['recorded_at'] ?? date('Y-m-d'));
        if ($existing) {
            return $this->update($existing['id'], $data);
        }

        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (user_id, water_intake, sleep_hours, mood_score, energy_level, stress_level, 
             exercise_minutes, steps_count, weight, calories_consumed, notes, recorded_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        
        $stmt->execute([
            $data['user_id'],
            $data['water_intake'] ?? 0,
            $data['sleep_hours'] ?? 0,
            $data['mood_score'] ?? null,
            $data['energy_level'] ?? null,
            $data['stress_level'] ?? null,
            $data['exercise_minutes'] ?? 0,
            $data['steps_count'] ?? 0,
            $data['weight'] ?? null,
            $data['calories_consumed'] ?? null,
            $data['notes'] ?? null,
            $data['recorded_at'] ?? date('Y-m-d')
        ]);
        
        return $stmt->fetch()['id'];
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET
                water_intake = ?,
                sleep_hours = ?,
                mood_score = ?,
                energy_level = ?,
                stress_level = ?,
                exercise_minutes = ?,
                steps_count = ?,
                weight = ?,
                calories_consumed = ?,
                notes = ?
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['water_intake'] ?? 0,
            $data['sleep_hours'] ?? 0,
            $data['mood_score'] ?? null,
            $data['energy_level'] ?? null,
            $data['stress_level'] ?? null,
            $data['exercise_minutes'] ?? 0,
            $data['steps_count'] ?? 0,
            $data['weight'] ?? null,
            $data['calories_consumed'] ?? null,
            $data['notes'] ?? null,
            $id
        ]);
    }
}
