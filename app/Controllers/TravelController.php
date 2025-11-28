<?php

namespace App\Controllers;

use App\Models\Travel;
use App\Models\Gamification;
use App\Core\Security;

class TravelController
{
    private $travelModel;
    private $gamification;

    public function __construct()
    {
        $this->travelModel = new Travel();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $trips = $this->travelModel->findByUserId($_SESSION['user_id']);
        $upcomingTrips = $this->travelModel->getUpcoming($_SESSION['user_id']);
        $pastTrips = $this->travelModel->getPast($_SESSION['user_id']);
        $totalBudget = $this->travelModel->getTotalBudget($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/travel/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /travel');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'destination' => Security::sanitizeInput($_POST['destination']),
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'budget' => floatval($_POST['budget'] ?? 0),
                'accommodation' => Security::sanitizeInput($_POST['accommodation'] ?? ''),
                'transportation' => Security::sanitizeInput($_POST['transportation'] ?? ''),
                'status' => 'planned',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->travelModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'trip_planned', 'Planned a trip');
                $_SESSION['success'] = 'Trip planned successfully';
            } else {
                $_SESSION['error'] = 'Failed to plan trip';
            }

            header('Location: /travel');
            exit;
        }

        require __DIR__ . '/../Views/modules/travel/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $trip = $this->travelModel->findById($id);
        
        if (!$trip || $trip['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Trip not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /travel');
                exit;
            }

            $data = [
                'destination' => Security::sanitizeInput($_POST['destination']),
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'budget' => floatval($_POST['budget'] ?? 0),
                'accommodation' => Security::sanitizeInput($_POST['accommodation'] ?? ''),
                'transportation' => Security::sanitizeInput($_POST['transportation'] ?? ''),
                'status' => $_POST['status'] ?? 'planned',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->travelModel->update($id, $data)) {
                $_SESSION['success'] = 'Trip updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update trip';
            }

            header('Location: /travel');
            exit;
        }

        require __DIR__ . '/../Views/modules/travel/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /travel');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /travel');
            exit;
        }

        $trip = $this->travelModel->findById($id);
        
        if ($trip && $trip['user_id'] == $_SESSION['user_id']) {
            $this->travelModel->delete($id);
            $_SESSION['success'] = 'Trip deleted successfully';
        }

        header('Location: /travel');
        exit;
    }
}
