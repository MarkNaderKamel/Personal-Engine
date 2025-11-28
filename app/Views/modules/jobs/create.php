<?php 
$pageTitle = 'Add Job Application'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2"></i>Add Job Application</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/jobs/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Title *</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" class="form-control" placeholder="City, State or Remote">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job Type</label>
                                <select name="job_type" class="form-select">
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Salary Range</label>
                                <input type="text" name="salary_range" class="form-control" placeholder="e.g., $80,000 - $100,000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Application Date</label>
                                <input type="date" name="application_date" class="form-control" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Job URL</label>
                                <input type="url" name="job_url" class="form-control" placeholder="https://...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Source</label>
                                <input type="text" name="source" class="form-control" placeholder="e.g., LinkedIn, Indeed, Referral">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="applied">Applied</option>
                                    <option value="reviewing">Under Review</option>
                                    <option value="interviewing">Interviewing</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Interview Date</label>
                                <input type="datetime-local" name="interview_date" class="form-control">
                            </div>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Contact Information</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Name</label>
                                <input type="text" name="contact_name" class="form-control" placeholder="Recruiter/HR name">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Email</label>
                                <input type="email" name="contact_email" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Contact Phone</label>
                                <input type="tel" name="contact_phone" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Job Description</label>
                            <textarea name="job_description" class="form-control" rows="3" placeholder="Paste or summarize the job description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes about this application"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Application
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
