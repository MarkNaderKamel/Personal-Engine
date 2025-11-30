<?php 
$pageTitle = 'Transactions'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Transactions</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cash-stack me-2 text-success"></i>Transactions</h2>
        <div class="d-flex gap-2">
            <a href="/transactions/export" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i>Export CSV
            </a>
            <a href="/transactions/report" class="btn btn-outline-info">
                <i class="bi bi-graph-up me-1"></i>Reports
            </a>
            <a href="/transactions/create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Add Transaction
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Income</h6>
                    <h3 class="mb-0">$<?= number_format($stats['total_income'] ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Expenses</h6>
                    <h3 class="mb-0">$<?= number_format($stats['total_expenses'] ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Net</h6>
                    <?php $net = ($stats['total_income'] ?? 0) - ($stats['total_expenses'] ?? 0); ?>
                    <h3 class="mb-0"><?= $net >= 0 ? '+' : '' ?>$<?= number_format($net, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="text-white-50">Total Balance</h6>
                    <h3 class="mb-0">$<?= number_format($balance ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-filter me-2"></i>Filter by Month</h5>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="/transactions" class="row g-3">
                <div class="col-md-5">
                    <select class="form-select" name="month">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $month ? 'selected' : '' ?>><?= $monthNames[$i-1] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" name="year">
                        <?php for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2 text-danger"></i>Expenses by Category</h6>
                </div>
                <div class="card-body">
                    <?php if (count($expensesByCategory) > 0): ?>
                    <?php foreach ($expensesByCategory as $cat): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><?= Security::sanitizeOutput($cat['category'] ?? 'Uncategorized') ?></span>
                        <span class="text-danger fw-bold">$<?= number_format($cat['total'], 2) ?></span>
                    </div>
                    <?php 
                    $percent = $stats['total_expenses'] > 0 ? ($cat['total'] / $stats['total_expenses']) * 100 : 0;
                    ?>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: <?= $percent ?>%"></div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No expenses this month</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-bar-chart me-2 text-success"></i>Income by Category</h6>
                </div>
                <div class="card-body">
                    <?php if (count($incomeByCategory) > 0): ?>
                    <?php foreach ($incomeByCategory as $cat): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><?= Security::sanitizeOutput($cat['category'] ?? 'Uncategorized') ?></span>
                        <span class="text-success fw-bold">$<?= number_format($cat['total'], 2) ?></span>
                    </div>
                    <?php 
                    $percent = $stats['total_income'] > 0 ? ($cat['total'] / $stats['total_income']) * 100 : 0;
                    ?>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: <?= $percent ?>%"></div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No income this month</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Transaction History - <?= $monthNames[$month-1] ?> <?= $year ?></h5>
        </div>
        <div class="card-body">
            <?php if (count($transactions) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Payment Method</th>
                            <th class="text-end">Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($t['transaction_date'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $t['transaction_type'] == 'income' ? 'success' : 'danger' ?>">
                                    <i class="bi bi-<?= $t['transaction_type'] == 'income' ? 'arrow-down' : 'arrow-up' ?> me-1"></i>
                                    <?= ucfirst($t['transaction_type']) ?>
                                </span>
                            </td>
                            <td><?= Security::sanitizeOutput($t['category'] ?? '-') ?></td>
                            <td><?= Security::sanitizeOutput($t['description'] ?? '-') ?></td>
                            <td><?= Security::sanitizeOutput($t['payment_method'] ?? '-') ?></td>
                            <td class="text-end fw-bold text-<?= $t['transaction_type'] == 'income' ? 'success' : 'danger' ?>">
                                <?= $t['transaction_type'] == 'income' ? '+' : '-' ?>$<?= number_format($t['amount'], 2) ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/transactions/edit/<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/transactions/delete/<?= $t['id'] ?>" style="display:inline;" onsubmit="return confirm('Delete this transaction?')">
                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-cash-coin display-1 text-muted opacity-25"></i>
                <p class="text-muted mt-3">No transactions found for this month.</p>
                <a href="/transactions/create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Add Your First Transaction
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
