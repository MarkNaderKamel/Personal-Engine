<?php

namespace App\Controllers;

use App\Models\Contract;
use App\Models\Gamification;
use App\Core\Security;

class ContractController
{
    private $contractModel;
    private $gamification;

    public function __construct()
    {
        $this->contractModel = new Contract();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $contracts = $this->contractModel->findByUserId($_SESSION['user_id']);
        $activeContracts = $this->contractModel->getActive($_SESSION['user_id']);
        $expiringContracts = $this->contractModel->getExpiring($_SESSION['user_id'], 30);
        $totalValue = $this->contractModel->getTotalValue($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/contracts/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /contracts');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'contract_name' => Security::sanitizeInput($_POST['contract_name']),
                'party_name' => Security::sanitizeInput($_POST['party_name'] ?? ''),
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'value' => floatval($_POST['value'] ?? 0),
                'status' => 'active',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->contractModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'contract_created', 'Created a contract');
                $_SESSION['success'] = 'Contract created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create contract';
            }

            header('Location: /contracts');
            exit;
        }

        require __DIR__ . '/../Views/modules/contracts/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $contract = $this->contractModel->findById($id);
        
        if (!$contract || $contract['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Contract not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /contracts');
                exit;
            }

            $data = [
                'contract_name' => Security::sanitizeInput($_POST['contract_name']),
                'party_name' => Security::sanitizeInput($_POST['party_name'] ?? ''),
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'value' => floatval($_POST['value'] ?? 0),
                'status' => $_POST['status'] ?? 'active',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->contractModel->update($id, $data)) {
                $_SESSION['success'] = 'Contract updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update contract';
            }

            header('Location: /contracts');
            exit;
        }

        require __DIR__ . '/../Views/modules/contracts/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contracts');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /contracts');
            exit;
        }

        $contract = $this->contractModel->findById($id);
        
        if ($contract && $contract['user_id'] == $_SESSION['user_id']) {
            $this->contractModel->delete($id);
            $_SESSION['success'] = 'Contract deleted successfully';
        }

        header('Location: /contracts');
        exit;
    }
}
