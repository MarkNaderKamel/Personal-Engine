<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\PantryItem;
use App\Models\Recipe;
use App\Models\Gamification;

class PantryController
{
    private $pantryModel;
    private $recipeModel;
    private $gamification;

    public function __construct()
    {
        $this->pantryModel = new PantryItem();
        $this->recipeModel = new Recipe();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $items = $this->pantryModel->findByUserId($userId);
        $stats = $this->pantryModel->getStats($userId);
        $expiringSoon = $this->pantryModel->getExpiringSoon($userId, 7);
        $expired = $this->pantryModel->getExpired($userId);
        $lowStock = $this->pantryModel->getLowStock($userId);
        $categories = $this->pantryModel->getCategorySummary($userId);
        
        require __DIR__ . '/../Views/modules/pantry/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /pantry');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'item_name' => Security::sanitizeInput($_POST['item_name']),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'quantity' => floatval($_POST['quantity'] ?? 1),
                'unit' => Security::sanitizeInput($_POST['unit'] ?? 'piece'),
                'expiry_date' => $_POST['expiry_date'] ?: null,
                'purchase_date' => $_POST['purchase_date'] ?: date('Y-m-d'),
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0) ?: null,
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'barcode' => Security::sanitizeInput($_POST['barcode'] ?? ''),
                'minimum_stock' => floatval($_POST['minimum_stock'] ?? 1),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->pantryModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'pantry_added', 'Added item to pantry');
                $_SESSION['success'] = 'Item added to pantry';
            } else {
                $_SESSION['error'] = 'Failed to add item';
            }

            header('Location: /pantry');
            exit;
        }

        require __DIR__ . '/../Views/modules/pantry/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $item = $this->pantryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /pantry');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /pantry');
                exit;
            }

            $data = [
                'item_name' => Security::sanitizeInput($_POST['item_name']),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'quantity' => floatval($_POST['quantity'] ?? 1),
                'unit' => Security::sanitizeInput($_POST['unit'] ?? 'piece'),
                'expiry_date' => $_POST['expiry_date'] ?: null,
                'purchase_date' => $_POST['purchase_date'] ?: null,
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0) ?: null,
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'barcode' => Security::sanitizeInput($_POST['barcode'] ?? ''),
                'minimum_stock' => floatval($_POST['minimum_stock'] ?? 1),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->pantryModel->update($id, $data)) {
                $_SESSION['success'] = 'Item updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update item';
            }

            header('Location: /pantry');
            exit;
        }

        require __DIR__ . '/../Views/modules/pantry/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pantry');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /pantry');
            exit;
        }

        $item = $this->pantryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /pantry');
            exit;
        }

        if ($this->pantryModel->delete($id)) {
            $_SESSION['success'] = 'Item removed from pantry';
        } else {
            $_SESSION['error'] = 'Failed to remove item';
        }

        header('Location: /pantry');
        exit;
    }

    public function adjustQuantity($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /pantry');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /pantry');
            exit;
        }

        $item = $this->pantryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /pantry');
            exit;
        }

        $action = $_POST['action'] ?? '';
        $amount = floatval($_POST['amount'] ?? 1);

        if ($action === 'add') {
            $this->pantryModel->addQuantity($id, $amount);
            $_SESSION['success'] = 'Quantity added';
        } elseif ($action === 'deduct') {
            $this->pantryModel->deductQuantity($id, $amount);
            $_SESSION['success'] = 'Quantity deducted';
        }

        header('Location: /pantry');
        exit;
    }

    public function search()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        $query = $_GET['q'] ?? '';
        
        $items = $this->pantryModel->search($userId, $query);
        
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }
}
