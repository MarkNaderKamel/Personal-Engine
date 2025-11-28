<?php

namespace App\Models;

use App\Core\Model;

class Document extends Model
{
    protected $table = 'documents';

    public function getByCategory($userId, $category)
    {
        $sql = "SELECT * FROM documents 
                WHERE user_id = :user_id 
                AND category = :category 
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'category' => $category
        ]);
    }

    public function searchDocuments($userId, $query)
    {
        $sql = "SELECT * FROM documents 
                WHERE user_id = :user_id 
                AND (file_name LIKE :query OR description LIKE :query)
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [
            'user_id' => $userId,
            'query' => "%{$query}%"
        ]);
    }

    public function getStorageUsed($userId)
    {
        $sql = "SELECT SUM(file_size) as total_size FROM documents WHERE user_id = :user_id";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result ? $result['total_size'] : 0;
    }
}
