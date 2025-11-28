<?php $pageTitle = 'Edit Subscription'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/subscriptions">Subscriptions</a></li>
            <li class="breadcrumb-item active">Edit Subscription</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Subscription</h2>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="/subscriptions/edit/<?= $subscription['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                
                <div class="mb-3">
                    <label for="service_name" class="form-label">Service Name *</label>
                    <input type="text" class="form-control" id="service_name" name="service_name" 
                           value="<?= htmlspecialchars($subscription['service_name']) ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="cost" class="form-label">Cost *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="cost" name="cost" 
                                   value="<?= $subscription['cost'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="billing_cycle" class="form-label">Billing Cycle</label>
                        <select class="form-select" id="billing_cycle" name="billing_cycle">
                            <option value="monthly" <?= $subscription['billing_cycle'] == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                            <option value="yearly" <?= $subscription['billing_cycle'] == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                            <option value="weekly" <?= $subscription['billing_cycle'] == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                            <option value="quarterly" <?= $subscription['billing_cycle'] == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="next_billing_date" class="form-label">Next Billing Date</label>
                        <input type="date" class="form-control" id="next_billing_date" name="next_billing_date" 
                               value="<?= $subscription['next_billing_date'] ?? '' ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <?php 
                            $categories = ['Entertainment', 'Productivity', 'Storage', 'Music', 'Video', 'Software', 'Gaming', 'News', 'Fitness', 'Other'];
                            foreach ($categories as $cat): 
                            ?>
                            <option value="<?= $cat ?>" <?= $subscription['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= $subscription['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="cancelled" <?= $subscription['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="paused" <?= $subscription['status'] == 'paused' ? 'selected' : '' ?>>Paused</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?= htmlspecialchars($subscription['notes'] ?? '') ?></textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                    <a href="/subscriptions" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
