<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Transaction;
use App\Models\Gamification;

class TransactionController
{
    private $model;
    private $gamification;

    public function __construct()
    {
        $this->model = new Transaction();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        
        $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $transactions = $this->model->getByDateRange($_SESSION['user_id'], $startDate, $endDate);
        $stats = $this->model->getMonthlyStats($_SESSION['user_id'], $month, $year);
        $expensesByCategory = $this->model->getExpensesByCategory($_SESSION['user_id'], $month, $year);
        $incomeByCategory = $this->model->getIncomeByCategory($_SESSION['user_id'], $month, $year);
        $balance = $this->model->getBalance($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/modules/transactions/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /transactions');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'transaction_type' => $_POST['transaction_type'],
                'amount' => floatval($_POST['amount']),
                'category' => Security::sanitizeInput($_POST['category']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'transaction_date' => $_POST['transaction_date'] ?: date('Y-m-d'),
                'payment_method' => Security::sanitizeInput($_POST['payment_method'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->create($data)) {
                $xp = $data['transaction_type'] === 'income' ? 15 : 10;
                $this->gamification->addXP($_SESSION['user_id'], $xp, 'transaction_added', 'Logged a ' . $data['transaction_type']);
                $_SESSION['success'] = ucfirst($data['transaction_type']) . ' recorded successfully';
            } else {
                $_SESSION['error'] = 'Failed to record transaction';
            }

            header('Location: /transactions');
            exit;
        }

        require __DIR__ . '/../Views/modules/transactions/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        
        $transaction = $this->model->findById($id);
        
        if (!$transaction || $transaction['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Transaction not found';
            header('Location: /transactions');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /transactions');
                exit;
            }

            $data = [
                'transaction_type' => $_POST['transaction_type'],
                'amount' => floatval($_POST['amount']),
                'category' => Security::sanitizeInput($_POST['category']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'transaction_date' => $_POST['transaction_date'] ?: date('Y-m-d'),
                'payment_method' => Security::sanitizeInput($_POST['payment_method'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = 'Transaction updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update transaction';
            }

            header('Location: /transactions');
            exit;
        }

        require __DIR__ . '/../Views/modules/transactions/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /transactions');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /transactions');
            exit;
        }

        $transaction = $this->model->findById($id);
        
        if ($transaction && $transaction['user_id'] == $_SESSION['user_id']) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Transaction deleted successfully';
        }

        header('Location: /transactions');
        exit;
    }

    public function report()
    {
        Security::requireAuth();
        
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        $yearlyStats = $this->model->getYearlyStats($_SESSION['user_id'], $year);
        $balance = $this->model->getBalance($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/modules/transactions/report.php';
    }

    public function exportCsv()
    {
        Security::requireAuth();
        
        $startDate = $_GET['start'] ?? date('Y-01-01');
        $endDate = $_GET['end'] ?? date('Y-m-d');
        
        $transactions = $this->model->getByDateRange($_SESSION['user_id'], $startDate, $endDate);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['Date', 'Type', 'Category', 'Description', 'Amount', 'Payment Method', 'Notes']);
        
        foreach ($transactions as $t) {
            fputcsv($output, [
                $t['transaction_date'],
                $t['transaction_type'],
                $t['category'],
                $t['description'],
                $t['amount'],
                $t['payment_method'],
                $t['notes']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
