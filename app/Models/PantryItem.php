<?php

namespace App\Models;

use App\Core\Model;

class PantryItem extends Model
{
    protected $table = 'pantry_items';

    public function findByUserId($userId, $orderBy = 'item_name ASC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findByCategory($userId, $category)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND category = ?
            ORDER BY item_name ASC
        ");
        $stmt->execute([$userId, $category]);
        return $stmt->fetchAll();
    }

    public function getExpiringSoon($userId, $days = 7)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND expiry_date IS NOT NULL
            AND expiry_date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL '{$days} days'
            ORDER BY expiry_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getExpired($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND expiry_date IS NOT NULL
            AND expiry_date < CURRENT_DATE
            ORDER BY expiry_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getLowStock($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND quantity <= minimum_stock
            ORDER BY item_name ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getStats($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_items,
                SUM(quantity * COALESCE(purchase_price, 0)) as total_value,
                COUNT(CASE WHEN expiry_date < CURRENT_DATE THEN 1 END) as expired_count,
                COUNT(CASE WHEN expiry_date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL '7 days' THEN 1 END) as expiring_soon,
                COUNT(CASE WHEN quantity <= minimum_stock THEN 1 END) as low_stock_count
            FROM {$this->table} 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function getCategorySummary($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                category,
                COUNT(*) as item_count,
                SUM(quantity) as total_quantity
            FROM {$this->table} 
            WHERE user_id = ?
            GROUP BY category
            ORDER BY item_count DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function search($userId, $query)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND LOWER(item_name) LIKE LOWER(?)
            ORDER BY item_name ASC
        ");
        $stmt->execute([$userId, "%{$query}%"]);
        return $stmt->fetchAll();
    }

    public function findByName($userId, $name)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND LOWER(item_name) = LOWER(?)
        ");
        $stmt->execute([$userId, $name]);
        return $stmt->fetch();
    }

    public function deductQuantity($id, $amount)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET quantity = GREATEST(0, quantity - ?),
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$amount, $id]);
    }

    public function addQuantity($id, $amount)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET quantity = quantity + ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$amount, $id]);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (user_id, item_name, category, quantity, unit, expiry_date, purchase_date,
             purchase_price, location, barcode, minimum_stock, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        
        $stmt->execute([
            $data['user_id'],
            $data['item_name'],
            $data['category'] ?? null,
            $data['quantity'] ?? 1,
            $data['unit'] ?? 'piece',
            $data['expiry_date'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['location'] ?? null,
            $data['barcode'] ?? null,
            $data['minimum_stock'] ?? 1,
            $data['notes'] ?? null
        ]);
        
        return $stmt->fetch()['id'];
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET
                item_name = ?,
                category = ?,
                quantity = ?,
                unit = ?,
                expiry_date = ?,
                purchase_date = ?,
                purchase_price = ?,
                location = ?,
                barcode = ?,
                minimum_stock = ?,
                notes = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['item_name'],
            $data['category'] ?? null,
            $data['quantity'] ?? 1,
            $data['unit'] ?? 'piece',
            $data['expiry_date'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['location'] ?? null,
            $data['barcode'] ?? null,
            $data['minimum_stock'] ?? 1,
            $data['notes'] ?? null,
            $id
        ]);
    }
}
