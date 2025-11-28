<?php

namespace App\Models;

use App\Core\Model;

class Gamification extends Model
{
    protected $table = 'user_xp';

    public function addXP($userId, $xp, $actionType, $description)
    {
        $this->db->beginTransaction();
        
        try {
            $this->db->insert('xp_transactions', [
                'user_id' => $userId,
                'action_type' => $actionType,
                'xp_earned' => $xp,
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            $userXp = $this->db->fetchOne(
                "SELECT * FROM user_xp WHERE user_id = :user_id",
                ['user_id' => $userId]
            );
            
            if (!$userXp) {
                $this->db->insert('user_xp', [
                    'user_id' => $userId,
                    'total_xp' => $xp,
                    'level' => 1
                ]);
            } else {
                $newTotalXp = $userXp['total_xp'] + $xp;
                $newLevel = floor($newTotalXp / 1000) + 1;
                
                $this->db->update(
                    'user_xp',
                    ['total_xp' => $newTotalXp, 'level' => $newLevel],
                    'user_id = :user_id',
                    ['user_id' => $userId]
                );
            }
            
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function updateStreak($userId)
    {
        $userXp = $this->db->fetchOne(
            "SELECT * FROM user_xp WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        
        if (!$userXp) return false;
        
        $today = date('Y-m-d');
        $lastActivity = $userXp['last_activity_date'];
        
        if ($lastActivity === $today) {
            return true;
        }
        
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        
        if ($lastActivity === $yesterday) {
            $newStreak = $userXp['current_streak'] + 1;
            $longestStreak = max($newStreak, $userXp['longest_streak']);
        } else {
            $newStreak = 1;
            $longestStreak = $userXp['longest_streak'];
        }
        
        return $this->db->update(
            'user_xp',
            [
                'current_streak' => $newStreak,
                'longest_streak' => $longestStreak,
                'last_activity_date' => $today
            ],
            'user_id = :user_id',
            ['user_id' => $userId]
        );
    }

    public function getUserStats($userId)
    {
        return $this->db->fetchOne(
            "SELECT * FROM user_xp WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
    }

    public function getRecentTransactions($userId, $limit = 10)
    {
        $sql = "SELECT * FROM xp_transactions 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT {$limit}";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function awardBadge($userId, $badgeName, $description)
    {
        return $this->db->insert('badges', [
            'user_id' => $userId,
            'badge_name' => $badgeName,
            'badge_description' => $description,
            'earned_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getUserBadges($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM badges WHERE user_id = :user_id ORDER BY earned_at DESC",
            ['user_id' => $userId]
        );
    }
}
