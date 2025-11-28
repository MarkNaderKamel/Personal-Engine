<?php 
$pageTitle = 'Edit Bill'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2>Edit Bill</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/bills/edit/<?= $bill['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="mb-3">
                    <label for="bill_name" class="form-label">Bill Name *</label>
                    <input type="text" class="form-control" id="bill_name" name="bill_name" 
                           value="<?= Security::sanitizeOutput($bill['bill_name']) ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount *</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                               value="<?= $bill['amount'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date *</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" 
                               value="<?= $bill['due_date'] ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="utilities" <?= $bill['category'] == 'utilities' ? 'selected' : '' ?>>Utilities</option>
                            <option value="rent" <?= $bill['category'] == 'rent' ? 'selected' : '' ?>>Rent</option>
                            <option value="insurance" <?= $bill['category'] == 'insurance' ? 'selected' : '' ?>>Insurance</option>
                            <option value="loan" <?= $bill['category'] == 'loan' ? 'selected' : '' ?>>Loan</option>
                            <option value="subscription" <?= $bill['category'] == 'subscription' ? 'selected' : '' ?>>Subscription</option>
                            <option value="other" <?= $bill['category'] == 'other' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" <?= $bill['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $bill['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="overdue" <?= $bill['status'] == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" 
                           <?= $bill['is_recurring'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_recurring">Recurring Bill</label>
                </div>
                
                <div class="mb-3" id="recurring_period_div" style="display:<?= $bill['is_recurring'] ? 'block' : 'none' ?>;">
                    <label for="recurring_period" class="form-label">Recurring Period</label>
                    <select class="form-select" id="recurring_period" name="recurring_period">
                        <option value="weekly" <?= $bill['recurring_period'] == 'weekly' ? 'selected' : '' ?>>Weekly</option>
                        <option value="monthly" <?= $bill['recurring_period'] == 'monthly' ? 'selected' : '' ?>>Monthly</option>
                        <option value="quarterly" <?= $bill['recurring_period'] == 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                        <option value="yearly" <?= $bill['recurring_period'] == 'yearly' ? 'selected' : '' ?>>Yearly</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($bill['notes'] ?? '') ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Bill</button>
                <a href="/bills" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('is_recurring').addEventListener('change', function() {
    document.getElementById('recurring_period_div').style.display = this.checked ? 'block' : 'none';
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
