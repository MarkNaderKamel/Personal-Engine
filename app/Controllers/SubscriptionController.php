<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Subscription;
use App\Models\Gamification;

class SubscriptionController
{
    private $subscriptionModel;
    private $gamification;

    public function __construct()
    {
        $this->subscriptionModel = new Subscription();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $subscriptions = $this->subscriptionModel->findByUserId($_SESSION['user_id']);
        $monthlyTotal = $this->subscriptionModel->getMonthlyTotal($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/subscriptions/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /subscriptions');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_name' => Security::sanitizeInput($_POST['service_name']),
                'cost' => floatval($_POST['cost']),
                'billing_cycle' => $_POST['billing_cycle'],
                'next_billing_date' => $_POST['next_billing_date'],
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'status' => 'active',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->subscriptionModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 10, 'subscription_added', 'Added a subscription');
                $_SESSION['success'] = 'Subscription added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add subscription';
            }

            header('Location: /subscriptions');
            exit;
        }

        require __DIR__ . '/../Views/modules/subscriptions/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        
        $subscription = $this->subscriptionModel->findById($id);
        
        if (!$subscription || $subscription['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Subscription not found';
            header('Location: /subscriptions');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /subscriptions');
                exit;
            }

            $data = [
                'service_name' => Security::sanitizeInput($_POST['service_name']),
                'cost' => floatval($_POST['cost']),
                'billing_cycle' => $_POST['billing_cycle'],
                'next_billing_date' => $_POST['next_billing_date'],
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'status' => $_POST['status'] ?? 'active',
                'notes' => Security::sanitizeInput($_POST['notes'] ?? '')
            ];

            if ($this->subscriptionModel->update($id, $data)) {
                $_SESSION['success'] = 'Subscription updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update subscription';
            }

            header('Location: /subscriptions');
            exit;
        }

        require __DIR__ . '/../Views/modules/subscriptions/edit.php';
    }

    public function toggleStatus($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /subscriptions');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /subscriptions');
            exit;
        }

        $subscription = $this->subscriptionModel->findById($id);
        
        if ($subscription && $subscription['user_id'] == $_SESSION['user_id']) {
            $newStatus = $subscription['status'] == 'active' ? 'cancelled' : 'active';
            $this->subscriptionModel->update($id, ['status' => $newStatus]);
            $_SESSION['success'] = 'Subscription status updated';
        }

        header('Location: /subscriptions');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /subscriptions');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /subscriptions');
            exit;
        }

        $subscription = $this->subscriptionModel->findById($id);
        
        if ($subscription && $subscription['user_id'] == $_SESSION['user_id']) {
            $this->subscriptionModel->delete($id);
            $_SESSION['success'] = 'Subscription deleted successfully';
        }

        header('Location: /subscriptions');
        exit;
    }
}
