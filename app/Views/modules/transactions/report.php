<?php 
$pageTitle = 'Financial Report'; 
include __DIR__ . '/../../layouts/header.php'; 

$monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$monthlyData = array_fill(1, 12, ['income' => 0, 'expenses' => 0]);

foreach ($yearlyStats as $stat) {
    $m = intval($stat['month']);
    $monthlyData[$m] = [
        'income' => floatval($stat['income']),
        'expenses' => floatval($stat['expenses'])
    ];
}

$totalIncome = array_sum(array_column($yearlyStats, 'income'));
$totalExpenses = array_sum(array_column($yearlyStats, 'expenses'));
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/transactions">Transactions</a></li>
            <li class="breadcrumb-item active">Report</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-graph-up me-2 text-info"></i>Financial Report - <?= $year ?></h2>
        <form method="GET" action="/transactions/report" class="d-flex gap-2">
            <select class="form-select" name="year" onchange="this.form.submit()">
                <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Total Income</h6>
                    <h2 class="mb-0">$<?= number_format($totalIncome, 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Total Expenses</h6>
                    <h2 class="mb-0">$<?= number_format($totalExpenses, 2) ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="text-white-50">Net Savings</h6>
                    <?php $net = $totalIncome - $totalExpenses; ?>
                    <h2 class="mb-0"><?= $net >= 0 ? '+' : '' ?>$<?= number_format($net, 2) ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Monthly Breakdown</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th class="text-end text-success">Income</th>
                            <th class="text-end text-danger">Expenses</th>
                            <th class="text-end">Net</th>
                            <th>Savings Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($m = 1; $m <= 12; $m++): 
                            $income = $monthlyData[$m]['income'];
                            $expenses = $monthlyData[$m]['expenses'];
                            $net = $income - $expenses;
                            $savingsRate = $income > 0 ? ($net / $income) * 100 : 0;
                        ?>
                        <tr class="<?= $m == date('n') && $year == date('Y') ? 'table-active' : '' ?>">
                            <td>
                                <strong><?= $monthNames[$m] ?></strong>
                                <?php if ($m == date('n') && $year == date('Y')): ?>
                                <span class="badge bg-primary ms-1">Current</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-success">$<?= number_format($income, 2) ?></td>
                            <td class="text-end text-danger">$<?= number_format($expenses, 2) ?></td>
                            <td class="text-end fw-bold <?= $net >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= $net >= 0 ? '+' : '' ?>$<?= number_format($net, 2) ?>
                            </td>
                            <td>
                                <?php if ($income > 0): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar <?= $savingsRate >= 0 ? 'bg-success' : 'bg-danger' ?>" 
                                             style="width: <?= min(abs($savingsRate), 100) ?>%"></div>
                                    </div>
                                    <span class="small <?= $savingsRate >= 0 ? 'text-success' : 'text-danger' ?>">
                                        <?= number_format($savingsRate, 1) ?>%
                                    </span>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th>Total</th>
                            <th class="text-end text-success">$<?= number_format($totalIncome, 2) ?></th>
                            <th class="text-end text-danger">$<?= number_format($totalExpenses, 2) ?></th>
                            <th class="text-end <?= $net >= 0 ? 'text-success' : 'text-danger' ?>">
                                <?= $net >= 0 ? '+' : '' ?>$<?= number_format($totalIncome - $totalExpenses, 2) ?>
                            </th>
                            <th>
                                <?php $totalSavingsRate = $totalIncome > 0 ? (($totalIncome - $totalExpenses) / $totalIncome) * 100 : 0; ?>
                                <span class="<?= $totalSavingsRate >= 0 ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($totalSavingsRate, 1) ?>%
                                </span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body text-center">
            <h5>Current Balance</h5>
            <h1 class="display-4 <?= $balance >= 0 ? 'text-success' : 'text-danger' ?>">
                $<?= number_format($balance, 2) ?>
            </h1>
            <p class="text-muted">All-time balance from all transactions</p>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
