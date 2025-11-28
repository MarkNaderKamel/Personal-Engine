<?php

namespace App\Controllers;

use App\Models\Debt;
use App\Models\Gamification;
use App\Core\Security;

class DebtController
{
    private $debtModel;
    private $gamification;

    public function __construct()
    {
        $this->debtModel = new Debt();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $debts = $this->debtModel->findByUserId($_SESSION['user_id']);
        $totalDebt = $this->debtModel->getTotalDebt($_SESSION['user_id']);
        $monthlyPayments = $this->debtModel->getMonthlyPayments($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/debts/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /debts');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'debt_name' => Security::sanitizeInput($_POST['debt_name']),
                'debt_type' => Security::sanitizeInput($_POST['debt_type'] ?? ''),
                'principal_amount' => floatval($_POST['principal_amount']),
                'current_balance' => floatval($_POST['current_balance']),
                'interest_rate' => floatval($_POST['interest_rate'] ?? 0),
                'minimum_payment' => floatval($_POST['minimum_payment'] ?? 0),
                'due_date' => $_POST['due_date'] ?? null,
                'creditor' => Security::sanitizeInput($_POST['creditor'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->debtModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'debt_added', 'Added a debt to track');
                $_SESSION['success'] = 'Debt added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add debt';
            }

            header('Location: /debts');
            exit;
        }

        require __DIR__ . '/../Views/modules/debts/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $debt = $this->debtModel->findById($id);
        
        if (!$debt || $debt['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Debt not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /debts');
                exit;
            }

            $data = [
                'debt_name' => Security::sanitizeInput($_POST['debt_name']),
                'debt_type' => Security::sanitizeInput($_POST['debt_type'] ?? ''),
                'principal_amount' => floatval($_POST['principal_amount']),
                'current_balance' => floatval($_POST['current_balance']),
                'interest_rate' => floatval($_POST['interest_rate'] ?? 0),
                'minimum_payment' => floatval($_POST['minimum_payment'] ?? 0),
                'due_date' => $_POST['due_date'] ?? null,
                'creditor' => Security::sanitizeInput($_POST['creditor'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->debtModel->update($id, $data)) {
                $_SESSION['success'] = 'Debt updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update debt';
            }

            header('Location: /debts');
            exit;
        }

        require __DIR__ . '/../Views/modules/debts/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /debts');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /debts');
            exit;
        }

        $debt = $this->debtModel->findById($id);
        
        if ($debt && $debt['user_id'] == $_SESSION['user_id']) {
            $this->debtModel->delete($id);
            $_SESSION['success'] = 'Debt deleted successfully';
        }

        header('Location: /debts');
        exit;
    }
}
