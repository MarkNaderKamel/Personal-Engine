<?php

namespace App\Models;

use App\Core\Model;

class Asset extends Model
{
    protected $table = 'assets';

    public function getTotalValue($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT SUM(current_value) as total FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $result['total'] ?? 0;
    }

    public function getByType($userId, $type)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND asset_type = :type ORDER BY current_value DESC",
            ['user_id' => $userId, 'type' => $type]
        );
    }
}
