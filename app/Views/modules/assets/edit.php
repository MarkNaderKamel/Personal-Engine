<?php 
$pageTitle = 'Edit Asset'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Asset</h5>
                </div>
                <div class="card-body">
                    <form action="/assets/edit/<?= $asset['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="asset_name" class="form-label">Asset Name *</label>
                                <input type="text" class="form-control" id="asset_name" name="asset_name" value="<?= Security::sanitizeOutput($asset['asset_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="asset_type" class="form-label">Asset Type</label>
                                <select class="form-select" id="asset_type" name="asset_type">
                                    <option value="">Select type</option>
                                    <option value="property" <?= $asset['asset_type'] === 'property' ? 'selected' : '' ?>>Property/Real Estate</option>
                                    <option value="vehicle" <?= $asset['asset_type'] === 'vehicle' ? 'selected' : '' ?>>Vehicle</option>
                                    <option value="investment" <?= $asset['asset_type'] === 'investment' ? 'selected' : '' ?>>Investment</option>
                                    <option value="savings" <?= $asset['asset_type'] === 'savings' ? 'selected' : '' ?>>Savings Account</option>
                                    <option value="jewelry" <?= $asset['asset_type'] === 'jewelry' ? 'selected' : '' ?>>Jewelry</option>
                                    <option value="electronics" <?= $asset['asset_type'] === 'electronics' ? 'selected' : '' ?>>Electronics</option>
                                    <option value="collectibles" <?= $asset['asset_type'] === 'collectibles' ? 'selected' : '' ?>>Collectibles</option>
                                    <option value="other" <?= $asset['asset_type'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_value" class="form-label">Current Value</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="current_value" name="current_value" value="<?= $asset['current_value'] ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="<?= $asset['purchase_price'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?= $asset['purchase_date'] ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="<?= Security::sanitizeOutput($asset['location']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($asset['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/assets" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Asset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
