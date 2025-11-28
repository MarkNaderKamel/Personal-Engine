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
                        <form method="POST" action="/budgets/delete/<?= $budget['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
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
