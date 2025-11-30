<?php

namespace App\Models;

use App\Core\Model;

class InventoryItem extends Model
{
    protected $table = 'inventory_items';

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

    public function findByRoom($userId, $room)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND room = ?
            ORDER BY item_name ASC
        ");
        $stmt->execute([$userId, $room]);
        return $stmt->fetchAll();
    }

    public function getExpiringWarranties($userId, $days = 30)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND warranty_expiry IS NOT NULL
            AND warranty_expiry BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL '{$days} days'
            ORDER BY warranty_expiry ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getTotalValue($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COALESCE(SUM(current_value), 0) as total_value,
                COALESCE(SUM(purchase_price), 0) as total_purchase,
                COALESCE(SUM(CASE WHEN is_insured THEN insurance_value ELSE 0 END), 0) as total_insured,
                COUNT(*) as total_items
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
                SUM(current_value) as total_value
            FROM {$this->table} 
            WHERE user_id = ?
            GROUP BY category
            ORDER BY total_value DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getRoomSummary($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                room,
                COUNT(*) as item_count,
                SUM(current_value) as total_value
            FROM {$this->table} 
            WHERE user_id = ?
            GROUP BY room
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
            AND (
                LOWER(item_name) LIKE LOWER(?) OR
                LOWER(brand) LIKE LOWER(?) OR
                LOWER(serial_number) LIKE LOWER(?) OR
                LOWER(location) LIKE LOWER(?)
            )
            ORDER BY item_name ASC
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([$userId, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (user_id, item_name, category, location, room, serial_number, model_number,
             brand, purchase_date, purchase_price, current_value, warranty_expiry,
             receipt_path, photo_path, condition, notes, is_insured, insurance_value)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        
        $stmt->execute([
            $data['user_id'],
            $data['item_name'],
            $data['category'] ?? null,
            $data['location'] ?? null,
            $data['room'] ?? null,
            $data['serial_number'] ?? null,
            $data['model_number'] ?? null,
            $data['brand'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['current_value'] ?? null,
            $data['warranty_expiry'] ?? null,
            $data['receipt_path'] ?? null,
            $data['photo_path'] ?? null,
            $data['condition'] ?? 'good',
            $data['notes'] ?? null,
            $data['is_insured'] ?? false,
            $data['insurance_value'] ?? null
        ]);
        
        return $stmt->fetch()['id'];
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET
                item_name = ?,
                category = ?,
                location = ?,
                room = ?,
                serial_number = ?,
                model_number = ?,
                brand = ?,
                purchase_date = ?,
                purchase_price = ?,
                current_value = ?,
                warranty_expiry = ?,
                receipt_path = COALESCE(?, receipt_path),
                photo_path = COALESCE(?, photo_path),
                condition = ?,
                notes = ?,
                is_insured = ?,
                insurance_value = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['item_name'],
            $data['category'] ?? null,
            $data['location'] ?? null,
            $data['room'] ?? null,
            $data['serial_number'] ?? null,
            $data['model_number'] ?? null,
            $data['brand'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['current_value'] ?? null,
            $data['warranty_expiry'] ?? null,
            $data['receipt_path'] ?? null,
            $data['photo_path'] ?? null,
            $data['condition'] ?? 'good',
            $data['notes'] ?? null,
            $data['is_insured'] ?? false,
            $data['insurance_value'] ?? null,
            $id
        ]);
    }
}
