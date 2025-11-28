<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Relationship;
use App\Models\Gamification;

class RelationshipController
{
    private $relationshipModel;
    private $gamification;

    public function __construct()
    {
        $this->relationshipModel = new Relationship();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $relationships = $this->relationshipModel->getAllByUser($userId);
        
        require __DIR__ . '/../Views/modules/relationships/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /relationships');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'person_name' => Security::sanitizeInput($_POST['person_name'] ?? ''),
                'relationship_type' => Security::sanitizeInput($_POST['relationship_type'] ?? ''),
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'important_dates' => Security::sanitizeInput($_POST['important_dates'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if (empty($data['person_name'])) {
                $_SESSION['error'] = 'Person name is required';
                header('Location: /relationships/create');
                exit;
            }

            if ($this->relationshipModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'relationship_add', 'Added relationship: ' . $data['person_name']);
                $_SESSION['success'] = 'Relationship added successfully';
                header('Location: /relationships');
            } else {
                $_SESSION['error'] = 'Failed to add relationship';
                header('Location: /relationships/create');
            }
            exit;
        }

        require __DIR__ . '/../Views/modules/relationships/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        
        $relationship = $this->relationshipModel->findByIdAndUser($id, $_SESSION['user_id']);
        
        if (!$relationship) {
            $_SESSION['error'] = 'Relationship not found';
            header('Location: /relationships');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /relationships');
                exit;
            }

            $data = [
                'person_name' => Security::sanitizeInput($_POST['person_name'] ?? ''),
                'relationship_type' => Security::sanitizeInput($_POST['relationship_type'] ?? ''),
                'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : null,
                'important_dates' => Security::sanitizeInput($_POST['important_dates'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->relationshipModel->updateByUser($id, $_SESSION['user_id'], $data)) {
                $_SESSION['success'] = 'Relationship updated successfully';
                header('Location: /relationships');
            } else {
                $_SESSION['error'] = 'Failed to update relationship';
                header('Location: /relationships/edit/' . $id);
            }
            exit;
        }

        require __DIR__ . '/../Views/modules/relationships/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /relationships');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /relationships');
            exit;
        }

        if ($this->relationshipModel->deleteByUser($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Relationship deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete relationship';
        }

        header('Location: /relationships');
        exit;
    }
}
