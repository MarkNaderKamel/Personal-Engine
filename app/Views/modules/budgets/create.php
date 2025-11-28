<?php $pageTitle = 'Create Budget'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Create Budget</h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <form method="POST" action="/budgets/create">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="month" class="form-label">Month *</label>
                        <select class="form-select" id="month" name="month" required>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == date('n') ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="year" class="form-label">Year *</label>
                        <input type="number" class="form-control" id="year" name="year" 
                               value="<?= date('Y') ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="category" class="form-label">Category *</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="Food">Food</option>
                        <option value="Transportation">Transportation</option>
                        <option value="Housing">Housing</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Healthcare">Healthcare</option>
                        <option value="Shopping">Shopping</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="budgeted_amount" class="form-label">Budgeted Amount *</label>
                    <input type="number" step="0.01" class="form-control" id="budgeted_amount" 
                           name="budgeted_amount" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Create Budget</button>
                <a href="/budgets" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
