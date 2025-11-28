<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Bill;
use App\Models\Gamification;
use App\Models\Notification;

class BillController
{
    private $billModel;
    private $gamification;
    private $notificationModel;

    public function __construct()
    {
        $this->billModel = new Bill();
        $this->gamification = new Gamification();
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        Security::requireAuth();
        $bills = $this->billModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/bills/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /bills');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'bill_name' => Security::sanitizeInput($_POST['bill_name']),
                'amount' => floatval($_POST['amount']),
                'due_date' => $_POST['due_date'],
                'is_recurring' => isset($_POST['is_recurring']),
                'recurring_period' => $_POST['recurring_period'] ?? null,
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->billModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'bill_added', 'Added a new bill');
                $_SESSION['success'] = 'Bill added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add bill';
            }

            header('Location: /bills');
            exit;
        }

        require __DIR__ . '/../Views/modules/bills/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $bill = $this->billModel->findById($id);
        
        if (!$bill || $bill['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Bill not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /bills');
                exit;
            }

            $data = [
                'bill_name' => Security::sanitizeInput($_POST['bill_name']),
                'amount' => floatval($_POST['amount']),
                'due_date' => $_POST['due_date'],
                'is_recurring' => isset($_POST['is_recurring']),
                'recurring_period' => $_POST['recurring_period'] ?? null,
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'status' => $_POST['status'] ?? 'pending',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->billModel->update($id, $data)) {
                $_SESSION['success'] = 'Bill updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update bill';
            }

            header('Location: /bills');
            exit;
        }

        require __DIR__ . '/../Views/modules/bills/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /bills');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /bills');
            exit;
        }

        $bill = $this->billModel->findById($id);
        
        if ($bill && $bill['user_id'] == $_SESSION['user_id']) {
            $this->billModel->delete($id);
            $_SESSION['success'] = 'Bill deleted successfully';
        }

        header('Location: /bills');
        exit;
    }
}
