<?php 
$pageTitle = 'Add Certification'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-award me-2"></i>Add Certification</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/resume/add-certification">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Certification Name *</label>
                            <input type="text" name="certification_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Issuing Organization</label>
                            <input type="text" name="issuing_organization" class="form-control" placeholder="e.g., AWS, Google, Microsoft">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Issue Date</label>
                                <input type="date" name="issue_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Expiry Date (if applicable)</label>
                                <input type="date" name="expiry_date" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Credential ID</label>
                            <input type="text" name="credential_id" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Credential URL</label>
                            <input type="url" name="credential_url" class="form-control" placeholder="https://...">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Certification
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
