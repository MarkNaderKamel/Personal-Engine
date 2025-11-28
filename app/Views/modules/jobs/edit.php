<?php 
$pageTitle = 'Edit Job Application'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Job Application</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/jobs/edit/<?= $application['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" value="<?= Security::sanitizeOutput($application['company_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="job_title" class="form-control" value="<?= Security::sanitizeOutput($application['job_title']) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" value="<?= Security::sanitizeOutput($application['location'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Type</label>
                                <select name="job_type" class="form-select">
                                    <option value="full-time" <?= ($application['job_type'] ?? '') === 'full-time' ? 'selected' : '' ?>>Full-time</option>
                                    <option value="part-time" <?= ($application['job_type'] ?? '') === 'part-time' ? 'selected' : '' ?>>Part-time</option>
                                    <option value="contract" <?= ($application['job_type'] ?? '') === 'contract' ? 'selected' : '' ?>>Contract</option>
                                    <option value="freelance" <?= ($application['job_type'] ?? '') === 'freelance' ? 'selected' : '' ?>>Freelance</option>
                                    <option value="internship" <?= ($application['job_type'] ?? '') === 'internship' ? 'selected' : '' ?>>Internship</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary_range" class="form-control" value="<?= Security::sanitizeOutput($application['salary_range'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Application Date</label>
                                <input type="date" name="application_date" class="form-control" value="<?= $application['application_date'] ?? '' ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job URL</label>
                                <input type="url" name="job_url" class="form-control" value="<?= Security::sanitizeOutput($application['job_url'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Source</label>
                                <input type="text" name="source" class="form-control" value="<?= Security::sanitizeOutput($application['source'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="applied" <?= $application['status'] === 'applied' ? 'selected' : '' ?>>Applied</option>
                                    <option value="reviewing" <?= $application['status'] === 'reviewing' ? 'selected' : '' ?>>Under Review</option>
                                    <option value="interviewing" <?= $application['status'] === 'interviewing' ? 'selected' : '' ?>>Interviewing</option>
                                    <option value="offered" <?= $application['status'] === 'offered' ? 'selected' : '' ?>>Offered</option>
                                    <option value="accepted" <?= $application['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                    <option value="rejected" <?= $application['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    <option value="withdrawn" <?= $application['status'] === 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="low" <?= ($application['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                                    <option value="medium" <?= ($application['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="high" <?= ($application['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Interview Date</label>
                                <input type="datetime-local" name="interview_date" class="form-control" value="<?= $application['interview_date'] ? date('Y-m-d\TH:i', strtotime($application['interview_date'])) : '' ?>">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Contact Information</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Name</label>
                                <input type="text" name="contact_name" class="form-control" value="<?= Security::sanitizeOutput($application['contact_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control" value="<?= Security::sanitizeOutput($application['contact_email'] ?? '') ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="tel" name="contact_phone" class="form-control" value="<?= Security::sanitizeOutput($application['contact_phone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Job Description</label>
                            <textarea name="job_description" class="form-control" rows="3"><?= Security::sanitizeOutput($application['job_description'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"><?= Security::sanitizeOutput($application['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Application
                            </button>
                            <a href="/jobs" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
