<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public function createNotification($userId, $type, $title, $message, $relatedId = null, $relatedType = null)
    {
        return $this->create([
            'user_id' => $userId,
            'notification_type' => $type,
            'title' => $title,
            'message' => $message,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getUnreadNotifications($userId)
    {
        $sql = "SELECT * FROM notifications 
                WHERE user_id = :user_id 
                AND is_read = FALSE 
                ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function markAsRead($notificationId, $userId)
    {
        return $this->db->update(
            'notifications',
            ['is_read' => true],
            'id = :id AND user_id = :user_id',
            ['id' => $notificationId, 'user_id' => $userId]
        );
    }

    public function markAllAsRead($userId)
    {
        return $this->db->update(
            'notifications',
            ['is_read' => true],
            'user_id = :user_id',
            ['user_id' => $userId]
        );
    }

    public function getUnreadCount($userId)
    {
        return $this->count('user_id = :user_id AND is_read = FALSE', ['user_id' => $userId]);
    }
}
