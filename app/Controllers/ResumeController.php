<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Resume;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Certification;
use App\Models\Gamification;

class ResumeController
{
    private $resumeModel;
    private $workModel;
    private $eduModel;
    private $skillModel;
    private $certModel;
    private $gamification;

    public function __construct()
    {
        $this->resumeModel = new Resume();
        $this->workModel = new WorkExperience();
        $this->eduModel = new Education();
        $this->skillModel = new Skill();
        $this->certModel = new Certification();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $resumes = $this->resumeModel->findByUserId($_SESSION['user_id']);
        $workExperience = $this->workModel->findByUserId($_SESSION['user_id']);
        $education = $this->eduModel->findByUserId($_SESSION['user_id']);
        $skills = $this->skillModel->findByUserId($_SESSION['user_id']);
        $certifications = $this->certModel->findByUserId($_SESSION['user_id']);
        require __DIR__ . '/../Views/modules/resume/index.php';
    }

    public function createResume()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $filePath = null;
            $fileType = null;
            
            if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../uploads/resumes/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $ext = strtolower(pathinfo($_FILES['resume_file']['name'], PATHINFO_EXTENSION));
                $allowedExts = ['pdf', 'doc', 'docx'];
                
                if (in_array($ext, $allowedExts)) {
                    $filename = uniqid('resume_') . '.' . $ext;
                    if (move_uploaded_file($_FILES['resume_file']['tmp_name'], $uploadDir . $filename)) {
                        $filePath = 'uploads/resumes/' . $filename;
                        $fileType = $ext;
                    }
                }
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'resume_name' => Security::sanitizeInput($_POST['resume_name']),
                'file_path' => $filePath,
                'file_type' => $fileType,
                'version' => Security::sanitizeInput($_POST['version'] ?? '1.0'),
                'is_default' => isset($_POST['is_default']),
                'target_role' => Security::sanitizeInput($_POST['target_role'] ?? ''),
                'summary' => Security::sanitizeInput($_POST['summary'] ?? '')
            ];

            if ($this->resumeModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 20, 'resume_created', 'Created a new resume');
                $_SESSION['success'] = 'Resume created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create resume';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/create-resume.php';
    }

    public function addExperience()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'company_name' => Security::sanitizeInput($_POST['company_name']),
                'job_title' => Security::sanitizeInput($_POST['job_title']),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'start_date' => $_POST['start_date'] ?: null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'is_current' => isset($_POST['is_current']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'achievements' => Security::sanitizeInput($_POST['achievements'] ?? ''),
                'skills_used' => Security::sanitizeInput($_POST['skills_used'] ?? '')
            ];

            if ($this->workModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'experience_added', 'Added work experience');
                $_SESSION['success'] = 'Work experience added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add work experience';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/add-experience.php';
    }

    public function editExperience($id)
    {
        Security::requireAuth();
        $experience = $this->workModel->findById($id);
        
        if (!$experience || $experience['user_id'] != $_SESSION['user_id']) {
            http_response_code(404);
            die('Experience not found');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $data = [
                'company_name' => Security::sanitizeInput($_POST['company_name']),
                'job_title' => Security::sanitizeInput($_POST['job_title']),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'start_date' => $_POST['start_date'] ?: null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'is_current' => isset($_POST['is_current']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'achievements' => Security::sanitizeInput($_POST['achievements'] ?? ''),
                'skills_used' => Security::sanitizeInput($_POST['skills_used'] ?? '')
            ];

            if ($this->workModel->update($id, $data)) {
                $_SESSION['success'] = 'Work experience updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update work experience';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/edit-experience.php';
    }

    public function deleteExperience($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $experience = $this->workModel->findById($id);
        if ($experience && $experience['user_id'] == $_SESSION['user_id']) {
            $this->workModel->delete($id);
            $_SESSION['success'] = 'Work experience deleted successfully';
        }

        header('Location: /resume');
        exit;
    }

    public function addEducation()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'institution_name' => Security::sanitizeInput($_POST['institution_name']),
                'degree' => Security::sanitizeInput($_POST['degree'] ?? ''),
                'field_of_study' => Security::sanitizeInput($_POST['field_of_study'] ?? ''),
                'location' => Security::sanitizeInput($_POST['location'] ?? ''),
                'start_date' => $_POST['start_date'] ?: null,
                'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : null,
                'is_current' => isset($_POST['is_current']),
                'gpa' => Security::sanitizeInput($_POST['gpa'] ?? ''),
                'achievements' => Security::sanitizeInput($_POST['achievements'] ?? '')
            ];

            if ($this->eduModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 15, 'education_added', 'Added education');
                $_SESSION['success'] = 'Education added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add education';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/add-education.php';
    }

    public function deleteEducation($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $education = $this->eduModel->findById($id);
        if ($education && $education['user_id'] == $_SESSION['user_id']) {
            $this->eduModel->delete($id);
            $_SESSION['success'] = 'Education deleted successfully';
        }

        header('Location: /resume');
        exit;
    }

    public function addSkill()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'skill_name' => Security::sanitizeInput($_POST['skill_name']),
                'skill_level' => $_POST['skill_level'] ?? 'intermediate',
                'category' => Security::sanitizeInput($_POST['category'] ?? 'Technical'),
                'years_experience' => intval($_POST['years_experience'] ?? 0)
            ];

            if ($this->skillModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 5, 'skill_added', 'Added a skill');
                $_SESSION['success'] = 'Skill added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add skill';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/add-skill.php';
    }

    public function deleteSkill($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $skill = $this->skillModel->findById($id);
        if ($skill && $skill['user_id'] == $_SESSION['user_id']) {
            $this->skillModel->delete($id);
            $_SESSION['success'] = 'Skill deleted successfully';
        }

        header('Location: /resume');
        exit;
    }

    public function addCertification()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /resume');
                exit;
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'certification_name' => Security::sanitizeInput($_POST['certification_name']),
                'issuing_organization' => Security::sanitizeInput($_POST['issuing_organization'] ?? ''),
                'issue_date' => $_POST['issue_date'] ?: null,
                'expiry_date' => !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null,
                'credential_id' => Security::sanitizeInput($_POST['credential_id'] ?? ''),
                'credential_url' => Security::sanitizeInput($_POST['credential_url'] ?? '')
            ];

            if ($this->certModel->create($data)) {
                $this->gamification->addXP($_SESSION['user_id'], 20, 'certification_added', 'Added a certification');
                $_SESSION['success'] = 'Certification added successfully';
            } else {
                $_SESSION['error'] = 'Failed to add certification';
            }

            header('Location: /resume');
            exit;
        }

        require __DIR__ . '/../Views/modules/resume/add-certification.php';
    }

    public function deleteCertification($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $cert = $this->certModel->findById($id);
        if ($cert && $cert['user_id'] == $_SESSION['user_id']) {
            $this->certModel->delete($id);
            $_SESSION['success'] = 'Certification deleted successfully';
        }

        header('Location: /resume');
        exit;
    }

    public function deleteResume($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $resume = $this->resumeModel->findById($id);
        if ($resume && $resume['user_id'] == $_SESSION['user_id']) {
            if ($resume['file_path'] && file_exists(__DIR__ . '/../../' . $resume['file_path'])) {
                unlink(__DIR__ . '/../../' . $resume['file_path']);
            }
            $this->resumeModel->delete($id);
            $_SESSION['success'] = 'Resume deleted successfully';
        }

        header('Location: /resume');
        exit;
    }

    public function setDefault($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /resume');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /resume');
            exit;
        }

        $resume = $this->resumeModel->findById($id);
        if ($resume && $resume['user_id'] == $_SESSION['user_id']) {
            $this->resumeModel->setDefault($id, $_SESSION['user_id']);
            $_SESSION['success'] = 'Default resume updated';
        }

        header('Location: /resume');
        exit;
    }
}
