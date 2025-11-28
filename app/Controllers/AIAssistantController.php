<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\AIConversation;
use App\Models\Gamification;

class AIAssistantController
{
    private $conversationModel;
    private $gamification;
    private $apiKey;

    public function __construct()
    {
        $this->conversationModel = new AIConversation();
        $this->gamification = new Gamification();
        $config = require __DIR__ . '/../../config/app.php';
        $this->apiKey = $config['openai_api_key'];
    }

    public function index()
    {
        Security::requireAuth();
        $history = $this->conversationModel->getConversationHistory($_SESSION['user_id'], 20);
        require __DIR__ . '/../Views/modules/ai/index.php';
    }

    public function chat()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /ai-assistant');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            echo json_encode(['error' => 'Invalid security token']);
            exit;
        }

        $userMessage = Security::sanitizeInput($_POST['message'] ?? '');
        
        if (empty($userMessage)) {
            echo json_encode(['error' => 'Message is required']);
            exit;
        }

        $aiResponse = $this->getAIResponse($userMessage);
        
        $this->conversationModel->saveConversation(
            $_SESSION['user_id'],
            $userMessage,
            $aiResponse
        );

        $this->gamification->addXP($_SESSION['user_id'], 5, 'ai_chat', 'Used AI assistant');

        header('Content-Type: application/json');
        echo json_encode(['response' => $aiResponse]);
        exit;
    }

    private function getAIResponse($message)
    {
        if (empty($this->apiKey)) {
            return "AI Assistant is not configured. Please set the OPENAI_API_KEY in your secrets.";
        }

        $context = $this->conversationModel->getRecentContext($_SESSION['user_id'], 3);
        
        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => 'You are a helpful life management assistant. Help users with productivity, finance, and personal life management. Be concise and actionable.'
        ];

        foreach ($context as $conv) {
            $messages[] = ['role' => 'user', 'content' => $conv['user_message']];
            if ($conv['ai_response']) {
                $messages[] = ['role' => 'assistant', 'content' => $conv['ai_response']];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.7
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return "I'm having trouble connecting to the AI service right now. Please try again later.";
        }

        $result = json_decode($response, true);
        
        if (isset($result['choices'][0]['message']['content'])) {
            return $result['choices'][0]['message']['content'];
        }

        return "I couldn't process that request. Please try again.";
    }
}
