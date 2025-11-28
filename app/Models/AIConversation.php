<?php

namespace App\Models;

use App\Core\Model;

class AIConversation extends Model
{
    protected $table = 'ai_conversations';

    public function saveConversation($userId, $userMessage, $aiResponse)
    {
        return $this->create([
            'user_id' => $userId,
            'user_message' => $userMessage,
            'ai_response' => $aiResponse,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function getConversationHistory($userId, $limit = 20)
    {
        $sql = "SELECT * FROM ai_conversations 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT {$limit}";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getRecentContext($userId, $limit = 5)
    {
        $history = $this->getConversationHistory($userId, $limit);
        return array_reverse($history);
    }
}
