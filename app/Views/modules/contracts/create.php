<?php 
$pageTitle = 'New Contract'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Create New Contract</h5>
                </div>
                <div class="card-body">
                    <form action="/contracts/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contract_name" class="form-label">Contract Name *</label>
                                <input type="text" class="form-control" id="contract_name" name="contract_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="party_name" class="form-label">Other Party</label>
                                <input type="text" class="form-control" id="party_name" name="party_name">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="value" class="form-label">Contract Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="value" name="value">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Contract details, terms, conditions..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/contracts" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Contract</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
