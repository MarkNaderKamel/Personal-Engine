<?php $pageTitle = 'Edit Budget'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/budgets">Budgets</a></li>
            <li class="breadcrumb-item active">Edit Budget</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Budget</h2>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="/budgets/edit/<?= $budget['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Month</label>
                        <input type="text" class="form-control" value="<?= date('F', mktime(0, 0, 0, $budget['month'], 1)) ?>" disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Year</label>
                        <input type="text" class="form-control" value="<?= $budget['year'] ?>" disabled>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Category *</label>
                    <select class="form-select" id="category" name="category" required>
                        <?php 
                        $categories = ['Food', 'Transportation', 'Housing', 'Entertainment', 'Healthcare', 'Shopping', 'Utilities', 'Education', 'Savings', 'Other'];
                        foreach ($categories as $cat): 
                        ?>
                        <option value="<?= $cat ?>" <?= $budget['category'] == $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="budgeted_amount" class="form-label">Budgeted Amount *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="budgeted_amount" 
                                   name="budgeted_amount" value="<?= $budget['budgeted_amount'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="spent_amount" class="form-label">Spent Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" class="form-control" id="spent_amount" 
                                   name="spent_amount" value="<?= $budget['spent_amount'] ?>">
                        </div>
                    </div>
                </div>
                
                <?php 
                $percent = $budget['budgeted_amount'] > 0 ? min(100, ($budget['spent_amount'] / $budget['budgeted_amount']) * 100) : 0;
                $statusClass = $percent > 90 ? 'bg-danger' : ($percent > 70 ? 'bg-warning' : 'bg-success');
                ?>
                <div class="mb-4">
                    <label class="form-label">Current Progress</label>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar <?= $statusClass ?>" role="progressbar" 
                             style="width: <?= $percent ?>%" 
                             aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($percent, 1) ?>%
                        </div>
                    </div>
                    <small class="text-muted">
                        Remaining: $<?= number_format($budget['budgeted_amount'] - $budget['spent_amount'], 2) ?>
                    </small>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Save Changes
                    </button>
                    <a href="/budgets" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
