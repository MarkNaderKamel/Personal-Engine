<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\CryptoAsset;
use App\Models\Gamification;

class CryptoController
{
    private $cryptoModel;
    private $gamification;

    public function __construct()
    {
        $this->cryptoModel = new CryptoAsset();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $cryptos = $this->cryptoModel->getAllByUser($userId);
        $totalValue = $this->cryptoModel->getTotalValue($userId);
        $totalInvested = $this->cryptoModel->getTotalInvested($userId);
        
        require __DIR__ . '/../Views/modules/crypto/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /crypto');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'coin_symbol' => strtoupper(Security::sanitizeInput($_POST['coin_symbol'] ?? '')),
                'coin_name' => Security::sanitizeInput($_POST['coin_name'] ?? ''),
                'amount' => floatval($_POST['amount'] ?? 0),
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0),
                'current_price' => floatval($_POST['current_price'] ?? 0),
                'alert_price' => !empty($_POST['alert_price']) ? floatval($_POST['alert_price']) : null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if (empty($data['coin_symbol']) || $data['amount'] <= 0) {
                $_SESSION['error'] = 'Coin symbol and amount are required';
                header('Location: /crypto/create');
                exit;
            }

            if ($this->cryptoModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'crypto_add', 'Added crypto asset: ' . $data['coin_symbol']);
                $_SESSION['success'] = 'Crypto asset added successfully';
                header('Location: /crypto');
            } else {
                $_SESSION['error'] = 'Failed to add crypto asset';
                header('Location: /crypto/create');
            }
            exit;
        }

        require __DIR__ . '/../Views/modules/crypto/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        
        $crypto = $this->cryptoModel->findByIdAndUser($id, $_SESSION['user_id']);
        
        if (!$crypto) {
            $_SESSION['error'] = 'Crypto asset not found';
            header('Location: /crypto');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /crypto');
                exit;
            }

            $data = [
                'coin_symbol' => strtoupper(Security::sanitizeInput($_POST['coin_symbol'] ?? '')),
                'coin_name' => Security::sanitizeInput($_POST['coin_name'] ?? ''),
                'amount' => floatval($_POST['amount'] ?? 0),
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0),
                'current_price' => floatval($_POST['current_price'] ?? 0),
                'alert_price' => !empty($_POST['alert_price']) ? floatval($_POST['alert_price']) : null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->cryptoModel->updateByUser($id, $_SESSION['user_id'], $data)) {
                $_SESSION['success'] = 'Crypto asset updated successfully';
                header('Location: /crypto');
            } else {
                $_SESSION['error'] = 'Failed to update crypto asset';
                header('Location: /crypto/edit/' . $id);
            }
            exit;
        }

        require __DIR__ . '/../Views/modules/crypto/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /crypto');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /crypto');
            exit;
        }

        if ($this->cryptoModel->deleteByUser($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Crypto asset deleted successfully';
        } else {
            $_SESSION['error'] = 'Failed to delete crypto asset';
        }

        header('Location: /crypto');
        exit;
    }
}
