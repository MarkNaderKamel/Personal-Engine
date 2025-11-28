<?php

namespace App\Models;

use App\Core\Model;

class Password extends Model
{
    protected $table = 'passwords';
    private $encryptionKey;

    public function __construct()
    {
        parent::__construct();
        $this->encryptionKey = getenv('PASSWORD_ENCRYPTION_KEY') ?: 'life_atlas_default_key_2025';
    }

    public function getAllForUser($userId)
    {
        return $this->db->fetchAll(
            "SELECT id, user_id, service_name, username, url, category, notes, created_at FROM {$this->table} WHERE user_id = :user_id ORDER BY service_name ASC",
            ['user_id' => $userId]
        );
    }

    public function getDecryptedPassword($id, $userId)
    {
        $record = $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :id AND user_id = :user_id",
            ['id' => $id, 'user_id' => $userId]
        );
        
        if ($record) {
            $record['decrypted_password'] = $this->decrypt($record['encrypted_password']);
        }
        return $record;
    }

    public function createPassword($data)
    {
        $data['encrypted_password'] = $this->encrypt($data['password']);
        unset($data['password']);
        return $this->create($data);
    }

    public function updatePassword($id, $data, $userId)
    {
        $existing = $this->findById($id);
        if (!$existing || $existing['user_id'] != $userId) {
            return false;
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $data['encrypted_password'] = $this->encrypt($data['password']);
            unset($data['password']);
        }

        return $this->db->update(
            $this->table,
            $data,
            'id = :id',
            ['id' => $id]
        );
    }

    private function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decrypt($data)
    {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $this->encryptionKey, 0, $iv);
    }

    public function getByCategory($userId, $category)
    {
        return $this->db->fetchAll(
            "SELECT id, user_id, service_name, username, url, category, notes, created_at FROM {$this->table} WHERE user_id = :user_id AND category = :category ORDER BY service_name ASC",
            ['user_id' => $userId, 'category' => $category]
        );
    }
}
