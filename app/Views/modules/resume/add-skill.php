<?php 
$pageTitle = 'Add Skill'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Add Skill</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/resume/add-skill">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Skill Name *</label>
                            <input type="text" name="skill_name" class="form-control" placeholder="e.g., Python, Project Management" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="Technical">Technical</option>
                                <option value="Programming">Programming</option>
                                <option value="Frameworks">Frameworks</option>
                                <option value="Tools">Tools</option>
                                <option value="Soft Skills">Soft Skills</option>
                                <option value="Languages">Languages</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Skill Level</label>
                            <select name="skill_level" class="form-select">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate" selected>Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Years of Experience</label>
                            <input type="number" name="years_experience" class="form-control" min="0" max="50" value="0">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Skill
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
