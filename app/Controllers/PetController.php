<?php

namespace App\Controllers;

use App\Models\Pet;
use App\Models\Gamification;
use App\Core\Security;

class PetController
{
    private $petModel;
    private $gamification;

    public function __construct()
    {
        $this->petModel = new Pet();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $pets = $this->petModel->findByUserId($_SESSION['user_id']);
        $upcomingCheckups = $this->petModel->getUpcomingCheckups($_SESSION['user_id'], 30);
        require __DIR__ . '/../Views/modules/pets/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /pets');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'pet_name' => Security::sanitizeInput($_POST['pet_name']),
                'pet_type' => Security::sanitizeInput($_POST['pet_type'] ?? ''),
                'breed' => Security::sanitizeInput($_POST['breed'] ?? ''),
                'birthday' => $_POST['birthday'] ?? null,
                'vet_name' => Security::sanitizeInput($_POST['vet_name'] ?? ''),
                'next_checkup' => $_POST['next_checkup'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->petModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'pet_added', 'Added a pet');
                $_SESSION['success'] = 'Pet added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add pet';
            }

            header('Location: /pets');
            exit;
        }

        require __DIR__ . '/../Views/modules/pets/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $pet = $this->petModel->findById($id);
        
        if (!$pet || $pet['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Pet not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /pets');
                exit;
            }

            $data = [
                'pet_name' => Security::sanitizeInput($_POST['pet_name']),
                'pet_type' => Security::sanitizeInput($_POST['pet_type'] ?? ''),
                'breed' => Security::sanitizeInput($_POST['breed'] ?? ''),
                'birthday' => $_POST['birthday'] ?? null,
                'vet_name' => Security::sanitizeInput($_POST['vet_name'] ?? ''),
                'next_checkup' => $_POST['next_checkup'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->petModel->update($id, $data)) {
                $_SESSION['success'] = 'Pet updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update pet';
            }

            header('Location: /pets');
            exit;
        }

        require __DIR__ . '/../Views/modules/pets/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pets');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /pets');
            exit;
        }

        $pet = $this->petModel->findById($id);
        
        if ($pet && $pet['user_id'] == $_SESSION['user_id']) {
            $this->petModel->delete($id);
            $_SESSION['success'] = 'Pet removed successfully';
        }

        header('Location: /pets');
        exit;
    }
}
