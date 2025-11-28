<?php

namespace App\Controllers;

use App\Models\Event;
use App\Models\Gamification;
use App\Core\Security;

class EventController
{
    private $eventModel;
    private $gamification;

    public function __construct()
    {
        $this->eventModel = new Event();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $month = isset($_GET['month']) ? intval($_GET['month']) : intval(date('n'));
        $year = isset($_GET['year']) ? intval($_GET['year']) : intval(date('Y'));
        
        $events = $this->eventModel->getByMonth($_SESSION['user_id'], $month, $year);
        $upcomingEvents = $this->eventModel->getUpcoming($_SESSION['user_id'], 7);
        $todayEvents = $this->eventModel->getTodayEvents($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/modules/events/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /events');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'event_title' => Security::sanitizeInput($_POST['event_title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? null,
                'location' => Security::sanitizeInput($_POST['location'] ?? '')
            ];

            if ($this->eventModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'event_created', 'Created an event');
                $_SESSION['success'] = 'Event created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create event';
            }

            header('Location: /events');
            exit;
        }

        require __DIR__ . '/../Views/modules/events/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $event = $this->eventModel->findById($id);
        
        if (!$event || $event['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Event not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /events');
                exit;
            }

            $data = [
                'event_title' => Security::sanitizeInput($_POST['event_title']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? null,
                'location' => Security::sanitizeInput($_POST['location'] ?? '')
            ];

            if ($this->eventModel->update($id, $data)) {
                $_SESSION['success'] = 'Event updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update event';
            }

            header('Location: /events');
            exit;
        }

        require __DIR__ . '/../Views/modules/events/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /events');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /events');
            exit;
        }

        $event = $this->eventModel->findById($id);
        
        if ($event && $event['user_id'] == $_SESSION['user_id']) {
            $this->eventModel->delete($id);
            $_SESSION['success'] = 'Event deleted successfully';
        }

        header('Location: /events');
        exit;
    }
}
