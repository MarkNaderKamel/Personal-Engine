<?php $pageTitle = 'Add Transaction'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/transactions">Transactions</a></li>
            <li class="breadcrumb-item active">Add Transaction</li>
        </ol>
    </nav>
    
    <h2><i class="bi bi-plus-circle me-2 text-primary"></i>Add Transaction</h2>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-body">
                    <form method="POST" action="/transactions/create">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="transaction_type" id="typeIncome" value="income" required>
                                    <label class="form-check-label text-success fw-bold" for="typeIncome">
                                        <i class="bi bi-arrow-down-circle me-1"></i>Income
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="transaction_type" id="typeExpense" value="expense" checked required>
                                    <label class="form-check-label text-danger fw-bold" for="typeExpense">
                                        <i class="bi bi-arrow-up-circle me-1"></i>Expense
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control form-control-lg" id="amount" name="amount" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transaction_date" class="form-label">Date *</label>
                                <input type="date" class="form-control form-control-lg" id="transaction_date" name="transaction_date" 
                                       value="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Select category...</option>
                                    <optgroup label="Income Categories">
                                        <option value="Salary">Salary</option>
                                        <option value="Freelance">Freelance</option>
                                        <option value="Investment">Investment</option>
                                        <option value="Business">Business</option>
                                        <option value="Gift">Gift</option>
                                        <option value="Other Income">Other Income</option>
                                    </optgroup>
                                    <optgroup label="Expense Categories">
                                        <option value="Food & Dining">Food & Dining</option>
                                        <option value="Transportation">Transportation</option>
                                        <option value="Housing">Housing</option>
                                        <option value="Utilities">Utilities</option>
                                        <option value="Entertainment">Entertainment</option>
                                        <option value="Shopping">Shopping</option>
                                        <option value="Healthcare">Healthcare</option>
                                        <option value="Education">Education</option>
                                        <option value="Personal Care">Personal Care</option>
                                        <option value="Subscriptions">Subscriptions</option>
                                        <option value="Insurance">Insurance</option>
                                        <option value="Travel">Travel</option>
                                        <option value="Gifts & Donations">Gifts & Donations</option>
                                        <option value="Other Expense">Other Expense</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" id="payment_method" name="payment_method">
                                    <option value="">Select method...</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="PayPal">PayPal</option>
                                    <option value="Venmo">Venmo</option>
                                    <option value="Crypto">Cryptocurrency</option>
                                    <option value="Check">Check</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="e.g., Grocery shopping at Walmart">
                        </div>
                        
                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Any additional notes..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-1"></i>Save Transaction
                            </button>
                            <a href="/transactions" class="btn btn-outline-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mt-4 bg-light">
                <div class="card-body">
                    <h6><i class="bi bi-lightbulb me-2 text-warning"></i>Quick Tips</h6>
                    <ul class="mb-0 small">
                        <li>Track all income and expenses for accurate budgeting</li>
                        <li>Use consistent categories for better reports</li>
                        <li>Add descriptions to remember transaction details</li>
                        <li>Review your transactions monthly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
