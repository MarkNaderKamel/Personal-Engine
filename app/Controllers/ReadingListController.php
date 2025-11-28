<?php

namespace App\Controllers;

use App\Models\ReadingList;
use App\Models\Gamification;
use App\Core\Security;

class ReadingListController
{
    private $readingModel;
    private $gamification;

    public function __construct()
    {
        $this->readingModel = new ReadingList();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $books = $this->readingModel->findByUserId($_SESSION['user_id']);
        $currentlyReading = $this->readingModel->getCurrentlyReading($_SESSION['user_id']);
        $stats = $this->readingModel->getStats($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/reading/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /reading');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'book_title' => Security::sanitizeInput($_POST['book_title']),
                'author' => Security::sanitizeInput($_POST['author'] ?? ''),
                'status' => $_POST['status'] ?? 'to_read',
                'rating' => !empty($_POST['rating']) ? intval($_POST['rating']) : null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'started_at' => $_POST['started_at'] ?? null,
                'completed_at' => $_POST['completed_at'] ?? null
            ];

            if ($this->readingModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'book_added', 'Added a book to reading list');
                $_SESSION['success'] = 'Book added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add book';
            }

            header('Location: /reading');
            exit;
        }

        require __DIR__ . '/../Views/modules/reading/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $book = $this->readingModel->findById($id);
        
        if (!$book || $book['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Book not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /reading');
                exit;
            }

            $oldStatus = $book['status'];
            $newStatus = $_POST['status'] ?? 'to_read';

            $data = [
                'book_title' => Security::sanitizeInput($_POST['book_title']),
                'author' => Security::sanitizeInput($_POST['author'] ?? ''),
                'status' => $newStatus,
                'rating' => !empty($_POST['rating']) ? intval($_POST['rating']) : null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'started_at' => $_POST['started_at'] ?? null,
                'completed_at' => $_POST['completed_at'] ?? null
            ];

            if ($this->readingModel->update($id, $data)) {
                if ($oldStatus !== 'completed' && $newStatus === 'completed') {
                    $this->gamification->addXP($_SESSION['user_id'], 25, 'book_completed', 'Completed reading a book');
                }
                $_SESSION['success'] = 'Book updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update book';
            }

            header('Location: /reading');
            exit;
        }

        require __DIR__ . '/../Views/modules/reading/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /reading');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /reading');
            exit;
        }

        $book = $this->readingModel->findById($id);
        
        if ($book && $book['user_id'] == $_SESSION['user_id']) {
            $this->readingModel->delete($id);
            $_SESSION['success'] = 'Book removed successfully';
        }

        header('Location: /reading');
        exit;
    }
}
