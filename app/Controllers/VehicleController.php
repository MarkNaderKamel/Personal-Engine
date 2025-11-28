<?php

namespace App\Controllers;

use App\Models\Vehicle;
use App\Models\Gamification;
use App\Core\Security;

class VehicleController
{
    private $vehicleModel;
    private $gamification;

    public function __construct()
    {
        $this->vehicleModel = new Vehicle();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $vehicles = $this->vehicleModel->findByUserId($_SESSION['user_id']);
        $upcomingService = $this->vehicleModel->getUpcomingService($_SESSION['user_id'], 30);
        $expiringInsurance = $this->vehicleModel->getExpiringInsurance($_SESSION['user_id'], 30);
        require __DIR__ . '/../Views/modules/vehicles/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /vehicles');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'make' => Security::sanitizeInput($_POST['make']),
                'model' => Security::sanitizeInput($_POST['model']),
                'year' => intval($_POST['year'] ?? 0),
                'license_plate' => Security::sanitizeInput($_POST['license_plate'] ?? ''),
                'next_service' => $_POST['next_service'] ?? null,
                'insurance_expiry' => $_POST['insurance_expiry'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->vehicleModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'vehicle_added', 'Added a vehicle');
                $_SESSION['success'] = 'Vehicle added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add vehicle';
            }

            header('Location: /vehicles');
            exit;
        }

        require __DIR__ . '/../Views/modules/vehicles/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $vehicle = $this->vehicleModel->findById($id);
        
        if (!$vehicle || $vehicle['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Vehicle not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /vehicles');
                exit;
            }

            $data = [
                'make' => Security::sanitizeInput($_POST['make']),
                'model' => Security::sanitizeInput($_POST['model']),
                'year' => intval($_POST['year'] ?? 0),
                'license_plate' => Security::sanitizeInput($_POST['license_plate'] ?? ''),
                'next_service' => $_POST['next_service'] ?? null,
                'insurance_expiry' => $_POST['insurance_expiry'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->vehicleModel->update($id, $data)) {
                $_SESSION['success'] = 'Vehicle updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update vehicle';
            }

            header('Location: /vehicles');
            exit;
        }

        require __DIR__ . '/../Views/modules/vehicles/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /vehicles');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /vehicles');
            exit;
        }

        $vehicle = $this->vehicleModel->findById($id);
        
        if ($vehicle && $vehicle['user_id'] == $_SESSION['user_id']) {
            $this->vehicleModel->delete($id);
            $_SESSION['success'] = 'Vehicle deleted successfully';
        }

        header('Location: /vehicles');
        exit;
    }
}
