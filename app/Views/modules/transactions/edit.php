<?php $pageTitle = 'Edit Transaction'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/transactions">Transactions</a></li>
            <li class="breadcrumb-item active">Edit Transaction</li>
        </ol>
    </nav>
    
    <h2><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Transaction</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/transactions/edit/<?= $transaction['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transaction_type" id="typeIncome" value="income" 
                                   <?= $transaction['transaction_type'] == 'income' ? 'checked' : '' ?> required>
                            <label class="form-check-label text-success fw-bold" for="typeIncome">
                                <i class="bi bi-arrow-down-circle me-1"></i>Income
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="transaction_type" id="typeExpense" value="expense" 
                                   <?= $transaction['transaction_type'] == 'expense' ? 'checked' : '' ?> required>
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
                            <input type="number" step="0.01" class="form-control form-control-lg" id="amount" name="amount" 
                                   value="<?= $transaction['amount'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="transaction_date" class="form-label">Date *</label>
                        <input type="date" class="form-control form-control-lg" id="transaction_date" name="transaction_date" 
                               value="<?= $transaction['transaction_date'] ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select category...</option>
                            <?php
                            $categories = [
                                'Income' => ['Salary', 'Freelance', 'Investment', 'Business', 'Gift', 'Other Income'],
                                'Expense' => ['Food & Dining', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Shopping', 'Healthcare', 'Education', 'Personal Care', 'Subscriptions', 'Insurance', 'Travel', 'Gifts & Donations', 'Other Expense']
                            ];
                            foreach ($categories as $group => $cats): ?>
                            <optgroup label="<?= $group ?> Categories">
                                <?php foreach ($cats as $cat): ?>
                                <option value="<?= $cat ?>" <?= $transaction['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Select method...</option>
                            <?php
                            $methods = ['Cash', 'Credit Card', 'Debit Card', 'Bank Transfer', 'PayPal', 'Venmo', 'Crypto', 'Check', 'Other'];
                            foreach ($methods as $method): ?>
                            <option value="<?= $method ?>" <?= $transaction['payment_method'] == $method ? 'selected' : '' ?>><?= $method ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" 
                           value="<?= htmlspecialchars($transaction['description'] ?? '') ?>">
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2"><?= htmlspecialchars($transaction['notes'] ?? '') ?></textarea>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                    <a href="/transactions" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
