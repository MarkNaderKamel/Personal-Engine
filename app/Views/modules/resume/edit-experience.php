<?php 
$pageTitle = 'Edit Work Experience'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Work Experience</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/resume/edit-experience/<?= $experience['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="job_title" class="form-control" value="<?= Security::sanitizeOutput($experience['job_title']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" value="<?= Security::sanitizeOutput($experience['company_name']) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" value="<?= Security::sanitizeOutput($experience['location'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="is_current" class="form-check-input" id="is_current" <?= $experience['is_current'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_current">I currently work here</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $experience['start_date'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control" id="end_date" value="<?= $experience['end_date'] ?? '' ?>" <?= $experience['is_current'] ? 'disabled' : '' ?>>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?= Security::sanitizeOutput($experience['description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Key Achievements</label>
                            <textarea name="achievements" class="form-control" rows="3"><?= Security::sanitizeOutput($experience['achievements'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Skills Used</label>
                            <input type="text" name="skills_used" class="form-control" value="<?= Security::sanitizeOutput($experience['skills_used'] ?? '') ?>">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Experience
                            </button>
                            <a href="/resume" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('is_current').addEventListener('change', function() {
    document.getElementById('end_date').disabled = this.checked;
    if (this.checked) document.getElementById('end_date').value = '';
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
