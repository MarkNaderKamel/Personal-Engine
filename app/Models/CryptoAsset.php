<?php

namespace App\Models;

use App\Core\Model;

class CryptoAsset extends Model
{
    protected $table = 'crypto_assets';

    public function getAllByUser($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY coin_symbol ASC",
            ['user_id' => $userId]
        );
    }

    public function findByIdAndUser($id, $userId)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id",
            ['id' => $id, 'user_id' => $userId]
        );
    }

    public function updateByUser($id, $userId, $data)
    {
        return $this->db->update($this->table, $data, 'id = :id AND user_id = :user_id', ['id' => $id, 'user_id' => $userId]);
    }

    public function deleteByUser($id, $userId)
    {
        return $this->db->delete($this->table, 'id = :id AND user_id = :user_id', ['id' => $id, 'user_id' => $userId]);
    }

    public function getTotalValue($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT COALESCE(SUM(amount * current_price), 0) as total FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return floatval($result['total'] ?? 0);
    }

    public function getTotalInvested($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT COALESCE(SUM(amount * purchase_price), 0) as total FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return floatval($result['total'] ?? 0);
    }

    public function getPortfolioValue($userId)
    {
        return $this->getTotalValue($userId);
    }

    public function updatePrices($userId, $prices)
    {
        foreach ($prices as $symbol => $price) {
            $this->db->query(
                "UPDATE crypto_assets SET current_price = :price WHERE user_id = :user_id AND coin_symbol = :symbol",
                ['price' => $price, 'user_id' => $userId, 'symbol' => $symbol]
            );
        }
    }
}
