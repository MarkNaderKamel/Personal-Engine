<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\JobApplication;
use App\Models\Gamification;
use App\Models\Notification;

class JobApplicationController
{
    private $model;
    private $gamification;
    private $notificationModel;

    public function __construct()
    {
        $this->model = new JobApplication();
        $this->gamification = new Gamification();
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        Security::requireAuth();
        $applications = $this->model->findByUserId($_SESSION['user_id']);
        $stats = $this->model->getStats($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/jobs/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /jobs');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'company_name' => Security::sanitizeInput($_POST['company_name']),
                'job_title' => Security::sanitizeInput($_POST['job_title']),
                'job_description' => Security::sanitizeInput($_POST['job_description'] ?? ''),
                'salary_range' => Security::sanitizeInput($_POST['salary_range'] ?? ''),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'job_type' => $_POST['job_type'] ?? 'full-time',
                'application_date' => $_POST['application_date'] ?: date('Y-m-d'),
                'status' => $_POST['status'] ?? 'applied',
                'source' => Security::sanitizeInput($_POST['source'] ?? ''),
                'job_url' => Security::sanitizeInput($_POST['job_url'] ?? ''),
                'contact_name' => Security::sanitizeInput($_POST['contact_name'] ?? ''),
                'contact_email' => Security::sanitizeInput($_POST['contact_email'] ?? ''),
                'contact_phone' => Security::sanitizeInput($_POST['contact_phone'] ?? ''),
                'interview_date' => !empty($_POST['interview_date']) ? $_POST['interview_date'] : null,
                'interview_type' => $_POST['interview_type'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'priority' => $_POST['priority'] ?? 'medium'
            ];

            if ($this->model->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'job_applied', 'Applied for a job: ' . $data['job_title']);
                $_SESSION['success'] = 'Job application added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add job application';
            }

            header('Location: /jobs');
            exit;
        }

        require __DIR__ . '/../Views/modules/jobs/create.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $application = $this->model->findById($id);
        
        if (!$application || $application['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Job application not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /jobs');
                exit;
            }

            $data = [
                'company_name' => Security::sanitizeInput($_POST['company_name']),
                'job_title' => Security::sanitizeInput($_POST['job_title']),
                'job_description' => Security::sanitizeInput($_POST['job_description'] ?? ''),
                'salary_range' => Security::sanitizeInput($_POST['salary_range'] ?? ''),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'job_type' => $_POST['job_type'] ?? 'full-time',
                'application_date' => $_POST['application_date'] ?: date('Y-m-d'),
                'status' => $_POST['status'] ?? 'applied',
                'source' => Security::sanitizeInput($_POST['source'] ?? ''),
                'job_url' => Security::sanitizeInput($_POST['job_url'] ?? ''),
                'contact_name' => Security::sanitizeInput($_POST['contact_name'] ?? ''),
                'contact_email' => Security::sanitizeInput($_POST['contact_email'] ?? ''),
                'contact_phone' => Security::sanitizeInput($_POST['contact_phone'] ?? ''),
                'interview_date' => !empty($_POST['interview_date']) ? $_POST['interview_date'] : null,
                'interview_type' => $_POST['interview_type'] ?? null,
                'notes' => Security::sanitizeInput($_POST['notes'] ?? ''),
                'priority' => $_POST['priority'] ?? 'medium'
            ];

            if ($this->model->update($id, $data)) {
                if ($data['status'] !== $application['status']) {
                    $this->gamification->addXP($_SESSION['user_id'], 10, 'job_status_update', 'Updated job status to: ' . $data['status']);
                }
                $_SESSION['success'] = 'Job application updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update job application';
            }

            header('Location: /jobs');
            exit;
        }

        require __DIR__ . '/../Views/modules/jobs/edit.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        $application = $this->model->findById($id);
        
        if (!$application || $application['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Job application not found');
        }

        require __DIR__ . '/../Views/modules/jobs/view.php';
    }

    public function updateStatus($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /jobs');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /jobs');
            exit;
        }

        $application = $this->model->findById($id);
        if (!$application || $application['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Job application not found';
            header('Location: /jobs');
            exit;
        }

        $status = $_POST['status'] ?? '';
        if ($this->model->updateStatus($id, $status)) {
            $xpAmount = 10;
            if ($status === 'offered') $xpAmount = 50;
            if ($status === 'accepted') $xpAmount = 100;
            
            $this->gamification->addXP($_SESSION['user_id'], $xpAmount, 'job_status_update', 'Job status: ' . $status);
            $_SESSION['success'] = 'Status updated successfully';
        }

        header('Location: /jobs');
        exit;
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /jobs');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /jobs');
            exit;
        }

        $application = $this->model->findById($id);
        if ($application && $application['user_id'] == $_SESSION['user_id']) {
            $this->model->delete($id);
            $_SESSION['success'] = 'Job application deleted successfully';
        }

        header('Location: /jobs');
        exit;
    }
}
