<?php 
$pageTitle = 'Add Crypto Asset'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Crypto Asset</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/crypto/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="coin_symbol" class="form-label">Coin Symbol *</label>
                                <input type="text" class="form-control" id="coin_symbol" name="coin_symbol" placeholder="BTC, ETH, SOL..." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="coin_name" class="form-label">Coin Name</label>
                                <input type="text" class="form-control" id="coin_name" name="coin_name" placeholder="Bitcoin, Ethereum...">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount *</label>
                                <input type="number" step="0.00000001" class="form-control" id="amount" name="amount" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_price" class="form-label">Current Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="current_price" name="current_price">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="alert_price" class="form-label">Alert Price (USD)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="alert_price" name="alert_price">
                                </div>
                                <small class="text-muted">Get notified when price reaches this level</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Trading notes, strategy, etc..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Add Crypto
                            </button>
                            <a href="/crypto" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
