<?php

namespace App\Models;

use App\Core\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';

    public function findByUserId($userId, $orderBy = 'platform', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, platform, profile_url, username, is_public, notes)
                VALUES (:user_id, :platform, :profile_url, :username, :is_public, :notes)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET platform = :platform, profile_url = :profile_url,
                username = :username, is_public = :is_public, notes = :notes WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function getPublicLinks($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND is_public = true ORDER BY platform",
            ['user_id' => $userId]
        );
    }
}
