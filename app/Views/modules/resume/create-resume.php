<?php 
$pageTitle = 'Upload Resume'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Resume</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/resume/create" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Resume Name *</label>
                            <input type="text" name="resume_name" class="form-control" placeholder="e.g., Software Engineer Resume 2024" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Resume File</label>
                            <input type="file" name="resume_file" class="form-control" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Accepted formats: PDF, DOC, DOCX</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Version</label>
                            <input type="text" name="version" class="form-control" placeholder="e.g., 1.0" value="1.0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Target Role</label>
                            <input type="text" name="target_role" class="form-control" placeholder="e.g., Senior Software Engineer">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Summary</label>
                            <textarea name="summary" class="form-control" rows="3" placeholder="Brief description or notes about this resume version"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_default" class="form-check-input" id="is_default">
                            <label class="form-check-label" for="is_default">Set as default resume</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-upload me-1"></i>Upload Resume
                            </button>
                            <a href="/resume" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
