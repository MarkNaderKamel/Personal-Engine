<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\InventoryItem;
use App\Models\Gamification;

class InventoryController
{
    private $inventoryModel;
    private $gamification;

    public function __construct()
    {
        $this->inventoryModel = new InventoryItem();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $items = $this->inventoryModel->findByUserId($userId);
        $totalValue = $this->inventoryModel->getTotalValue($userId);
        $categorySummary = $this->inventoryModel->getCategorySummary($userId);
        $roomSummary = $this->inventoryModel->getRoomSummary($userId);
        $expiringWarranties = $this->inventoryModel->getExpiringWarranties($userId, 60);
        
        require __DIR__ . '/../Views/modules/inventory/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /inventory');
                exit;
            }

            $photoPath = null;
            $receiptPath = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoPath = $this->handleFileUpload($_FILES['photo'], 'inventory');
            }

            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $receiptPath = $this->handleFileUpload($_FILES['receipt'], 'receipts');
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'item_name' => Security::sanitizeInput($_POST['item_name']),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'room' => Security::sanitizeInput($_POST['room'] ?? ''),
                'serial_number' => Security::sanitizeInput($_POST['serial_number'] ?? ''),
                'model_number' => Security::sanitizeInput($_POST['model_number'] ?? ''),
                'brand' => Security::sanitizeInput($_POST['brand'] ?? ''),
                'purchase_date' => $_POST['purchase_date'] ?: null,
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0) ?: null,
                'current_value' => floatval($_POST['current_value'] ?? 0) ?: null,
                'warranty_expiry' => $_POST['warranty_expiry'] ?: null,
                'photo_path' => $photoPath,
                'receipt_path' => $receiptPath,
                'condition' => $_POST['condition'] ?? 'good',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'is_insured' => isset($_POST['is_insured']),
                'insurance_value' => floatval($_POST['insurance_value'] ?? 0) ?: null
            ];

            if ($this->inventoryModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'inventory_added', 'Added item to inventory');
                $_SESSION['success'] = 'Item added to inventory';
            } else {
                $_SESSION['error'] = 'Failed to add item';
            }

            header('Location: /inventory');
            exit;
        }

        require __DIR__ . '/../Views/modules/inventory/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $item = $this->inventoryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /inventory');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /inventory');
                exit;
            }

            $photoPath = null;
            $receiptPath = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoPath = $this->handleFileUpload($_FILES['photo'], 'inventory');
            }

            if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
                $receiptPath = $this->handleFileUpload($_FILES['receipt'], 'receipts');
            }

            $data = [
                'item_name' => Security::sanitizeInput($_POST['item_name']),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'room' => Security::sanitizeInput($_POST['room'] ?? ''),
                'serial_number' => Security::sanitizeInput($_POST['serial_number'] ?? ''),
                'model_number' => Security::sanitizeInput($_POST['model_number'] ?? ''),
                'brand' => Security::sanitizeInput($_POST['brand'] ?? ''),
                'purchase_date' => $_POST['purchase_date'] ?: null,
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0) ?: null,
                'current_value' => floatval($_POST['current_value'] ?? 0) ?: null,
                'warranty_expiry' => $_POST['warranty_expiry'] ?: null,
                'photo_path' => $photoPath,
                'receipt_path' => $receiptPath,
                'condition' => $_POST['condition'] ?? 'good',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'is_insured' => isset($_POST['is_insured']),
                'insurance_value' => floatval($_POST['insurance_value'] ?? 0) ?: null
            ];

            if ($this->inventoryModel->update($id, $data)) {
                $_SESSION['success'] = 'Item updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update item';
            }

            header('Location: /inventory');
            exit;
        }

        require __DIR__ . '/../Views/modules/inventory/edit.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        $item = $this->inventoryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /inventory');
            exit;
        }

        require __DIR__ . '/../Views/modules/inventory/view.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /inventory');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /inventory');
            exit;
        }

        $item = $this->inventoryModel->findById($id);
        
        if (!$item || $item['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Item not found';
            header('Location: /inventory');
            exit;
        }

        if ($this->inventoryModel->delete($id)) {
            $_SESSION['success'] = 'Item deleted from inventory';
        } else {
            $_SESSION['error'] = 'Failed to delete item';
        }

        header('Location: /inventory');
        exit;
    }

    public function search()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        $query = $_GET['q'] ?? '';
        
        $items = $this->inventoryModel->search($userId, $query);
        
        header('Content-Type: application/json');
        echo json_encode($items);
        exit;
    }

    public function exportCsv()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $items = $this->inventoryModel->findByUserId($userId);
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="home_inventory_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, [
            'Item Name', 'Category', 'Room', 'Location', 'Brand', 'Model', 
            'Serial Number', 'Purchase Date', 'Purchase Price', 'Current Value',
            'Warranty Expiry', 'Condition', 'Insured', 'Insurance Value', 'Notes'
        ]);
        
        foreach ($items as $item) {
            fputcsv($output, [
                $item['item_name'],
                $item['category'],
                $item['room'],
                $item['location'],
                $item['brand'],
                $item['model_number'],
                $item['serial_number'],
                $item['purchase_date'],
                $item['purchase_price'],
                $item['current_value'],
                $item['warranty_expiry'],
                $item['condition'],
                $item['is_insured'] ? 'Yes' : 'No',
                $item['insurance_value'],
                $item['notes']
            ]);
        }
        
        fclose($output);
        exit;
    }

    private function handleFileUpload($file, $folder)
    {
        $uploadDir = __DIR__ . '/../../uploads/' . $folder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid($folder . '_') . '.' . $ext;
        
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return 'uploads/' . $folder . '/' . $filename;
        }
        
        return null;
    }
}
