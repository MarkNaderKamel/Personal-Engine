<?php 
$pageTitle = 'Edit Contract'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Contract</h5>
                </div>
                <div class="card-body">
                    <form action="/contracts/edit/<?= $contract['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contract_name" class="form-label">Contract Name *</label>
                                <input type="text" class="form-control" id="contract_name" name="contract_name" value="<?= Security::sanitizeOutput($contract['contract_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="party_name" class="form-label">Other Party</label>
                                <input type="text" class="form-control" id="party_name" name="party_name" value="<?= Security::sanitizeOutput($contract['party_name']) ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $contract['start_date'] ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $contract['end_date'] ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="value" class="form-label">Contract Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="value" name="value" value="<?= $contract['value'] ?>">
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="active" <?= $contract['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="expired" <?= $contract['status'] === 'expired' ? 'selected' : '' ?>>Expired</option>
                                    <option value="cancelled" <?= $contract['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4"><?= Security::sanitizeOutput($contract['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/contracts" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Contract</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
