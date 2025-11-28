<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Document;
use App\Models\Gamification;

class DocumentController
{
    private $documentModel;
    private $gamification;

    public function __construct()
    {
        $this->documentModel = new Document();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $documents = $this->documentModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/documents/index.php';
    }

    public function upload()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /documents');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /documents');
            exit;
        }

        if (!isset($_FILES['document'])) {
            $_SESSION['error'] = 'No file uploaded';
            header('Location: /documents');
            exit;
        }

        $file = $_FILES['document'];
        $validation = Security::validateFileUpload($file);
        
        if (!$validation['success']) {
            $_SESSION['error'] = $validation['message'];
            header('Location: /documents');
            exit;
        }

        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = __DIR__ . '/../../uploads/documents/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'file_name' => $fileName,
                'original_name' => $file['name'],
                'file_path' => 'uploads/documents/' . $fileName,
                'file_type' => $validation['ext'],
                'file_size' => $file['size'],
                'category' => Security::sanitizeInput($_POST['category'] ?? 'general'),
                'description' => Security::sanitizeInput($_POST['description'] ?? '')
            ];

            if ($this->documentModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'document_upload', 'Uploaded a document');
                $_SESSION['success'] = 'Document uploaded successfully';
            }
        } else {
            $_SESSION['error'] = 'Failed to upload document';
        }

        header('Location: /documents');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /documents');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /documents');
            exit;
        }

        $document = $this->documentModel->findById($id);
        
        if ($document && $document['user_id'] == $_SESSION['user_id']) {
            $filePath = __DIR__ . '/../../' . $document['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->documentModel->delete($id);
            $_SESSION['success'] = 'Document deleted successfully';
        }

        header('Location: /documents');
        exit;
    }
}
