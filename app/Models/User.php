<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Security;

class User extends Model
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]
        );
    }

    public function register($data)
    {
        $data['password'] = Security::hashPassword($data['password']);
        $data['verification_token'] = Security::generateRandomToken();
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $userId = $this->create($data);
        
        if ($userId) {
            $this->db->insert('user_xp', [
                'user_id' => $userId,
                'total_xp' => 0,
                'level' => 1,
                'current_streak' => 0
            ]);
        }
        
        return $userId;
    }

    public function verifyEmail($token)
    {
        return $this->db->update(
            'users',
            ['email_verified' => true, 'verification_token' => null],
            'verification_token = :token',
            ['token' => $token]
        );
    }

    public function createPasswordResetToken($email)
    {
        $token = Security::generateRandomToken();
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        return $this->db->update(
            'users',
            ['reset_token' => $token, 'reset_token_expires' => $expires],
            'email = :email',
            ['email' => $email]
        );
    }

    public function resetPassword($token, $newPassword)
    {
        $user = $this->db->fetchOne(
            "SELECT * FROM users WHERE reset_token = :token AND reset_token_expires > NOW()",
            ['token' => $token]
        );
        
        if (!$user) {
            return false;
        }
        
        return $this->db->update(
            'users',
            [
                'password' => Security::hashPassword($newPassword),
                'reset_token' => null,
                'reset_token_expires' => null
            ],
            'id = :id',
            ['id' => $user['id']]
        );
    }

    public function updateProfile($userId, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($userId, $data);
    }

    public function getAllUsers()
    {
        return $this->db->fetchAll("SELECT id, email, first_name, last_name, role, email_verified, created_at FROM users ORDER BY created_at DESC");
    }

    public function deleteUser($userId)
    {
        return $this->delete($userId);
    }
}
