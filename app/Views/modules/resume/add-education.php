<?php 
$pageTitle = 'Add Education'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Add Education</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/resume/add-education">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Institution Name *</label>
                            <input type="text" name="institution_name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Degree</label>
                                <select name="degree" class="form-select">
                                    <option value="">Select Degree</option>
                                    <option value="High School Diploma">High School Diploma</option>
                                    <option value="Associate's Degree">Associate's Degree</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="Doctorate">Doctorate (PhD)</option>
                                    <option value="Certificate">Certificate</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Field of Study</label>
                                <input type="text" name="field_of_study" class="form-control" placeholder="e.g., Computer Science">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="City, State">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">GPA (Optional)</label>
                                <input type="text" name="gpa" class="form-control" placeholder="e.g., 3.8/4.0">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date (or Expected)</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_current" class="form-check-input" id="is_current">
                            <label class="form-check-label" for="is_current">Currently studying here</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Achievements/Activities</label>
                            <textarea name="achievements" class="form-control" rows="3" placeholder="Dean's List, Clubs, Activities, etc."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Education
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
