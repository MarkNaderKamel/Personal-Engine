<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Budget;
use App\Models\Gamification;

class BudgetController
{
    private $budgetModel;
    private $gamification;

    public function __construct()
    {
        $this->budgetModel = new Budget();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $budgets = $this->budgetModel->getCurrentMonthBudgets($_SESSION['user_id']);
        $summary = $this->budgetModel->getBudgetSummary($_SESSION['user_id'], date('n'), date('Y'));
        require __DIR__ . '/../Views/modules/budgets/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /budgets');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'month' => intval($_POST['month']),
                'year' => intval($_POST['year']),
                'category' => Security::sanitizeInput($_POST['category']),
                'budgeted_amount' => floatval($_POST['budgeted_amount']),
                'spent_amount' => 0
            ];

            if ($this->budgetModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'budget_created', 'Created a budget');
                $_SESSION['success'] = 'Budget created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create budget';
            }

            header('Location: /budgets');
            exit;
        }

        require __DIR__ . '/../Views/modules/budgets/create.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /budgets');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /budgets');
            exit;
        }

        $budget = $this->budgetModel->findById($id);
        
        if ($budget && $budget['user_id'] == $_SESSION['user_id']) {
            $this->budgetModel->delete($id);
            $_SESSION['success'] = 'Budget deleted successfully';
        }

        header('Location: /budgets');
        exit;
    }
}
