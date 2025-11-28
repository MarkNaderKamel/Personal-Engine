<?php $pageTitle = 'Add Subscription'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Add Subscription</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/subscriptions/create">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="mb-3">
                    <label for="service_name" class="form-label">Service Name *</label>
                    <input type="text" class="form-control" id="service_name" name="service_name" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cost" class="form-label">Cost *</label>
                        <input type="number" step="0.01" class="form-control" id="cost" name="cost" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="billing_cycle" class="form-label">Billing Cycle *</label>
                        <select class="form-select" id="billing_cycle" name="billing_cycle" required>
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="quarterly">Quarterly</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="next_billing_date" class="form-label">Next Billing Date *</label>
                        <input type="date" class="form-control" id="next_billing_date" name="next_billing_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="Streaming">Streaming</option>
                            <option value="Software">Software</option>
                            <option value="Gaming">Gaming</option>
                            <option value="News">News</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Add Subscription</button>
                <a href="/subscriptions" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
