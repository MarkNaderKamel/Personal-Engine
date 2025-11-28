<?php

namespace App\Models;

use App\Core\Model;

class CryptoAsset extends Model
{
    protected $table = 'crypto_assets';

    public function getPortfolioValue($userId)
    {
        $assets = $this->findByUserId($userId);
        $totalValue = 0;
        
        foreach ($assets as $asset) {
            $totalValue += $asset['amount'] * ($asset['current_price'] ?? 0);
        }
        
        return $totalValue;
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
