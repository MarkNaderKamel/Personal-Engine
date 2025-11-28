<?php $pageTitle = 'Add Bill'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Add New Bill</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/bills/create">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="mb-3">
                    <label for="bill_name" class="form-label">Bill Name *</label>
                    <input type="text" class="form-control" id="bill_name" name="bill_name" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount *</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date *</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="utilities">Utilities</option>
                            <option value="rent">Rent</option>
                            <option value="insurance">Insurance</option>
                            <option value="loan">Loan</option>
                            <option value="subscription">Subscription</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring">
                            <label class="form-check-label" for="is_recurring">Recurring Bill</label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3" id="recurring_period_div" style="display:none;">
                    <label for="recurring_period" class="form-label">Recurring Period</label>
                    <select class="form-select" id="recurring_period" name="recurring_period">
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="quarterly">Quarterly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Bill</button>
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
