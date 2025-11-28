<?php

namespace App\Models;

use App\Core\Model;

class Note extends Model
{
    protected $table = 'notes';

    public function getFavorites($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND is_favorite = true ORDER BY created_at DESC",
            ['user_id' => $userId]
        );
    }

    public function toggleFavorite($id, $userId)
    {
        $note = $this->findById($id);
        if ($note && $note['user_id'] == $userId) {
            $newValue = !$note['is_favorite'];
            return $this->db->update(
                $this->table,
                ['is_favorite' => $newValue],
                'id = :id',
                ['id' => $id]
            );
        }
        return false;
    }

    public function searchNotes($userId, $query)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND (title ILIKE :query OR content ILIKE :query) ORDER BY created_at DESC",
            ['user_id' => $userId, 'query' => "%{$query}%"]
        );
    }
}
