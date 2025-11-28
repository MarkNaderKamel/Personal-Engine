<?php 
$pageTitle = 'Debt Tracker'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-credit-card-2-front me-2"></i>Debt Tracker</h2>
        <a href="/debts/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Debt
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6><i class="bi bi-cash-stack me-2"></i>Total Debt</h6>
                    <h3>$<?= number_format($totalDebt, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6><i class="bi bi-calendar-check me-2"></i>Monthly Payments</h6>
                    <h3>$<?= number_format($monthlyPayments, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($debts) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Original Amount</th>
                            <th>Current Balance</th>
                            <th>Interest Rate</th>
                            <th>Min. Payment</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($debts as $debt): ?>
                        <tr>
                            <td>
                                <strong><?= Security::sanitizeOutput($debt['debt_name']) ?></strong>
                                <?php if ($debt['creditor']): ?>
                                <br><small class="text-muted"><?= Security::sanitizeOutput($debt['creditor']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?= Security::sanitizeOutput($debt['debt_type'] ?: 'Other') ?></span></td>
                            <td>$<?= number_format($debt['principal_amount'], 2) ?></td>
                            <td class="text-danger fw-bold">$<?= number_format($debt['current_balance'], 2) ?></td>
                            <td><?= $debt['interest_rate'] ?>%</td>
                            <td>$<?= number_format($debt['minimum_payment'], 2) ?></td>
                            <td>
                                <?php if ($debt['due_date']): ?>
                                    <?= date('M d', strtotime($debt['due_date'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/debts/edit/<?= $debt['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/debts/delete/<?= $debt['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this debt?')">
                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-credit-card-2-front display-1 text-muted"></i>
            <h4 class="mt-3">No debts tracked</h4>
            <p class="text-muted">Start tracking your debts to manage your finances better.</p>
            <a href="/debts/create" class="btn btn-primary">Add Your First Debt</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
