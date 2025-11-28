<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Contact;
use App\Models\Gamification;

class ContactController
{
    private $contactModel;
    private $gamification;

    public function __construct()
    {
        $this->contactModel = new Contact();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $contacts = $this->contactModel->findByUserId($_SESSION['user_id']);
        $upcomingBirthdays = $this->contactModel->getUpcomingBirthdays($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/contacts/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /contacts');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => Security::sanitizeInput($_POST['full_name']),
                'email' => Security::sanitizeInput($_POST['email'] ?? ''),
                'phone' => Security::sanitizeInput($_POST['phone'] ?? ''),
                'company' => Security::sanitizeInput($_POST['company'] ?? ''),
                'relationship' => Security::sanitizeInput($_POST['relationship'] ?? ''),
                'birthday' => $_POST['birthday'] ?? null,
                'address' => Security::sanitizeInput($_POST['address'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->contactModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'contact_added', 'Added a contact');
                $_SESSION['success'] = 'Contact added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add contact';
            }

            header('Location: /contacts');
            exit;
        }

        require __DIR__ . '/../Views/modules/contacts/create.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contacts');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /contacts');
            exit;
        }

        $contact = $this->contactModel->findById($id);
        
        if ($contact && $contact['user_id'] == $_SESSION['user_id']) {
            $this->contactModel->delete($id);
            $_SESSION['success'] = 'Contact deleted successfully';
        }

        header('Location: /contacts');
        exit;
    }
}
