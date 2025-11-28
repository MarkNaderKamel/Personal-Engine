<?php

namespace App\Models;

use App\Core\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    public function getActiveSubscriptions($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM subscriptions WHERE user_id = :user_id AND status = 'active' ORDER BY next_billing_date ASC",
            ['user_id' => $userId]
        );
    }

    public function getMonthlyTotal($userId)
    {
        $sql = "SELECT SUM(cost) as total FROM subscriptions 
                WHERE user_id = :user_id 
                AND status = 'active' 
                AND billing_cycle = 'monthly'";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result ? $result['total'] : 0;
    }

    public function getUpcomingRenewals($userId, $days = 7)
    {
        $sql = "SELECT * FROM subscriptions 
                WHERE user_id = :user_id 
                AND status = 'active'
                AND next_billing_date <= CURRENT_DATE + INTERVAL '{$days} days'
                ORDER BY next_billing_date ASC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}
