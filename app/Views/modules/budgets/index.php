<?php 
$pageTitle = 'Budgets'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Budget Planning</h2>
        <a href="/budgets/create" class="btn btn-primary">Create Budget</a>
    </div>
    
    <?php if ($summary): ?>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5>This Month Summary</h5>
                    <p class="mb-1">Total Budgeted: $<?= number_format($summary['total_budgeted'] ?? 0, 2) ?></p>
                    <p class="mb-1">Total Spent: $<?= number_format($summary['total_spent'] ?? 0, 2) ?></p>
                    <p class="mb-0">Remaining: $<?= number_format(($summary['total_budgeted'] ?? 0) - ($summary['total_spent'] ?? 0), 2) ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (count($budgets) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Month/Year</th>
                    <th>Budgeted</th>
                    <th>Spent</th>
                    <th>Remaining</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($budgets as $budget): ?>
                <?php
                    $remaining = $budget['budgeted_amount'] - $budget['spent_amount'];
                    $percentage = ($budget['spent_amount'] / $budget['budgeted_amount']) * 100;
                ?>
                <tr>
                    <td><?= Security::sanitizeOutput($budget['category']) ?></td>
                    <td><?= $budget['month'] ?>/<?= $budget['year'] ?></td>
                    <td>$<?= number_format($budget['budgeted_amount'], 2) ?></td>
                    <td>$<?= number_format($budget['spent_amount'], 2) ?></td>
                    <td class="<?= $remaining < 0 ? 'text-danger' : 'text-success' ?>">
                        $<?= number_format($remaining, 2) ?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/budgets/edit/<?= $budget['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-success" title="Add Expense" 
                                    data-bs-toggle="modal" data-bs-target="#addExpenseModal<?= $budget['id'] ?>">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            <form method="POST" action="/budgets/delete/<?= $budget['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this budget?')">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        
                        <div class="modal fade" id="addExpenseModal<?= $budget['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Expense to <?= Security::sanitizeOutput($budget['category']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="/budgets/add-expense/<?= $budget['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="amount<?= $budget['id'] ?>" class="form-label">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" step="0.01" class="form-control" id="amount<?= $budget['id'] ?>" name="amount" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add Expense</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No budgets found. <a href="/budgets/create">Create your first budget</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
