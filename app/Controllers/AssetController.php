<?php

namespace App\Controllers;

use App\Models\Asset;
use App\Models\Gamification;
use App\Core\Security;

class AssetController
{
    private $assetModel;
    private $gamification;

    public function __construct()
    {
        $this->assetModel = new Asset();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $assets = $this->assetModel->findByUserId($_SESSION['user_id']);
        $totalValue = $this->assetModel->getTotalValue($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/assets/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /assets');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'asset_name' => Security::sanitizeInput($_POST['asset_name']),
                'asset_type' => Security::sanitizeInput($_POST['asset_type'] ?? ''),
                'current_value' => floatval($_POST['current_value'] ?? 0),
                'purchase_date' => $_POST['purchase_date'] ?? null,
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->assetModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'asset_added', 'Added an asset');
                $_SESSION['success'] = 'Asset added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add asset';
            }

            header('Location: /assets');
            exit;
        }

        require __DIR__ . '/../Views/modules/assets/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $asset = $this->assetModel->findById($id);
        
        if (!$asset || $asset['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Asset not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /assets');
                exit;
            }

            $data = [
                'asset_name' => Security::sanitizeInput($_POST['asset_name']),
                'asset_type' => Security::sanitizeInput($_POST['asset_type'] ?? ''),
                'current_value' => floatval($_POST['current_value'] ?? 0),
                'purchase_date' => $_POST['purchase_date'] ?? null,
                'purchase_price' => floatval($_POST['purchase_price'] ?? 0),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->assetModel->update($id, $data)) {
                $_SESSION['success'] = 'Asset updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update asset';
            }

            header('Location: /assets');
            exit;
        }

        require __DIR__ . '/../Views/modules/assets/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /assets');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /assets');
            exit;
        }

        $asset = $this->assetModel->findById($id);
        
        if ($asset && $asset['user_id'] == $_SESSION['user_id']) {
            $this->assetModel->delete($id);
            $_SESSION['success'] = 'Asset deleted successfully';
        }

        header('Location: /assets');
        exit;
    }
}
