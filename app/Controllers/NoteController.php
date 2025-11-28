<?php

namespace App\Controllers;

use App\Models\Note;
use App\Models\Gamification;
use App\Core\Security;

class NoteController
{
    private $noteModel;
    private $gamification;

    public function __construct()
    {
        $this->noteModel = new Note();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $notes = $this->noteModel->findByUserId($_SESSION['user_id']);
        $favorites = $this->noteModel->getFavorites($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/notes/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /notes');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'title' => Security::sanitizeInput($_POST['title']),
                'content' => Security::sanitizeInput($_POST['content'] ?? ''),
                'note_type' => 'text',
                'tags' => Security::sanitizeInput($_POST['tags'] ?? ''),
                'is_favorite' => isset($_POST['is_favorite'])
            ];

            if ($this->noteModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'note_created', 'Created a note');
                $_SESSION['success'] = 'Note created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create note';
            }

            header('Location: /notes');
            exit;
        }

        require __DIR__ . '/../Views/modules/notes/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $note = $this->noteModel->findById($id);
        
        if (!$note || $note['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Note not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /notes');
                exit;
            }

            $data = [
                'title' => Security::sanitizeInput($_POST['title']),
                'content' => Security::sanitizeInput($_POST['content'] ?? ''),
                'tags' => Security::sanitizeInput($_POST['tags'] ?? ''),
                'is_favorite' => isset($_POST['is_favorite'])
            ];

            if ($this->noteModel->update($id, $data)) {
                $_SESSION['success'] = 'Note updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update note';
            }

            header('Location: /notes');
            exit;
        }

        require __DIR__ . '/../Views/modules/notes/edit.php';
    }

    public function toggleFavorite($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /notes');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /notes');
            exit;
        }

        if ($this->noteModel->toggleFavorite($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Note updated';
        }

        header('Location: /notes');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /notes');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /notes');
            exit;
        }

        $note = $this->noteModel->findById($id);
        
        if ($note && $note['user_id'] == $_SESSION['user_id']) {
            $this->noteModel->delete($id);
            $_SESSION['success'] = 'Note deleted successfully';
        }

        header('Location: /notes');
        exit;
    }
}
