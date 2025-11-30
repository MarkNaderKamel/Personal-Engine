<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Financial Forecaster</h1>
            <p class="text-muted mb-0">Predict your future financial health</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Current Balance</p>
                            <h3 class="mb-0">$<?= number_format($currentBalance, 0) ?></h3>
                        </div>
                        <i class="bi bi-wallet2 display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Monthly Income</p>
                            <h3 class="mb-0">$<?= number_format($monthlyIncome, 0) ?></h3>
                        </div>
                        <i class="bi bi-arrow-up-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Monthly Expenses</p>
                            <h3 class="mb-0">$<?= number_format($monthlyExpenses, 0) ?></h3>
                        </div>
                        <i class="bi bi-arrow-down-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card <?= $savingsRate >= 20 ? 'bg-info' : ($savingsRate >= 0 ? 'bg-warning' : 'bg-danger') ?> text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Savings Rate</p>
                            <h3 class="mb-0"><?= number_format($savingsRate, 1) ?>%</h3>
                        </div>
                        <i class="bi bi-piggy-bank display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>12-Month Balance Forecast</h5>
                </div>
                <div class="card-body">
                    <canvas id="forecastChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bullseye me-2"></i>Goal Calculator</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Target Amount ($)</label>
                        <input type="number" class="form-control form-control-lg" id="goalTarget" value="10000" min="0" step="1000">
                    </div>
                    <button class="btn btn-primary w-100 mb-3" onclick="calculateGoal()">Calculate</button>
                    <div id="goalResult" class="d-none">
                        <div class="alert alert-info">
                            <strong>Time to Goal:</strong>
                            <span id="goalMonths" class="fs-4 d-block"></span>
                            <small id="goalDate" class="text-muted"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Upcoming Bills (30 Days)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($upcomingBills)): ?>
                        <p class="text-muted text-center py-3">No upcoming bills</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Bill</th>
                                        <th>Due Date</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingBills as $bill): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($bill['name']) ?>
                                                <?php if ($bill['is_recurring']): ?>
                                                    <i class="bi bi-arrow-repeat text-muted ms-1" title="Recurring"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= strtotime($bill['due_date']) <= strtotime('+7 days') ? 'warning' : 'secondary' ?>">
                                                    <?= date('M j', strtotime($bill['due_date'])) ?>
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">$<?= number_format($bill['amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-light">
                                        <td colspan="2"><strong>Total Due</strong></td>
                                        <td class="text-end fw-bold">$<?= number_format(array_sum(array_column($upcomingBills, 'amount')), 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-repeat me-2"></i>Monthly Recurring Costs</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <i class="bi bi-arrow-repeat text-primary fs-2"></i>
                                <h5 class="mt-2 mb-0">$<?= number_format($subscriptionTotal, 0) ?></h5>
                                <small class="text-muted">Subscriptions</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <i class="bi bi-credit-card text-danger fs-2"></i>
                                <h5 class="mt-2 mb-0">$<?= number_format($debtPayments, 0) ?></h5>
                                <small class="text-muted">Debt Payments</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Total Fixed Monthly Costs:</span>
                        <strong class="fs-5">$<?= number_format($subscriptionTotal + $debtPayments, 0) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Top Expense Categories (3 Months)</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($expensesByCategory)): ?>
                        <p class="text-muted text-center py-3">No expense data</p>
                    <?php else: ?>
                        <?php $maxExpense = max(array_column($expensesByCategory, 'total')); ?>
                        <?php foreach ($expensesByCategory as $cat): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?= htmlspecialchars($cat['category'] ?? 'Other') ?></span>
                                    <span class="fw-bold">$<?= number_format($cat['total'], 0) ?></span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: <?= ($cat['total'] / $maxExpense) * 100 ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Income vs Expenses History</h5>
                </div>
                <div class="card-body">
                    <canvas id="historyChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Financial Insights</h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <?php if ($savingsRate < 0): ?>
                    <div class="col-md-6">
                        <div class="alert alert-danger mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Overspending Alert:</strong> You're spending more than you earn. Review your expenses to find areas to cut back.
                        </div>
                    </div>
                <?php elseif ($savingsRate < 10): ?>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Low Savings:</strong> Aim for at least 20% savings rate for financial security.
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-md-6">
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Good Progress:</strong> You're saving <?= number_format($savingsRate, 1) ?>% of your income. Keep it up!
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($subscriptionTotal > $monthlyIncome * 0.1): ?>
                    <div class="col-md-6">
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            <strong>Subscription Review:</strong> Your subscriptions are over 10% of income. Consider auditing them.
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php 
                $netMonthly = $monthlyIncome - $monthlyExpenses;
                $emergencyFund = $currentBalance / $monthlyExpenses;
                if ($emergencyFund < 3 && $monthlyExpenses > 0): ?>
                    <div class="col-md-6">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-shield me-2"></i>
                            <strong>Emergency Fund:</strong> You have <?= number_format($emergencyFund, 1) ?> months of expenses saved. Aim for 3-6 months.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const forecastData = <?= json_encode($forecastData) ?>;
const historyData = <?= json_encode($incomeHistory) ?>;

const forecastCtx = document.getElementById('forecastChart').getContext('2d');
new Chart(forecastCtx, {
    type: 'line',
    data: {
        labels: forecastData.map(d => d.month),
        datasets: [{
            label: 'Projected Balance',
            data: forecastData.map(d => d.projected_balance),
            borderColor: 'rgb(99, 102, 241)',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: false,
                ticks: {
                    callback: value => '$' + value.toLocaleString()
                }
            }
        }
    }
});

const historyCtx = document.getElementById('historyChart').getContext('2d');
new Chart(historyCtx, {
    type: 'bar',
    data: {
        labels: historyData.map(d => d.month),
        datasets: [
            {
                label: 'Income',
                data: historyData.map(d => d.income),
                backgroundColor: 'rgba(16, 185, 129, 0.8)'
            },
            {
                label: 'Expenses',
                data: historyData.map(d => d.expense),
                backgroundColor: 'rgba(239, 68, 68, 0.8)'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                ticks: {
                    callback: value => '$' + value.toLocaleString()
                }
            }
        }
    }
});

function calculateGoal() {
    const target = document.getElementById('goalTarget').value;
    fetch('/forecast/goal?target=' + target)
        .then(r => r.json())
        .then(data => {
            const resultDiv = document.getElementById('goalResult');
            const monthsSpan = document.getElementById('goalMonths');
            const dateSpan = document.getElementById('goalDate');
            
            resultDiv.classList.remove('d-none');
            
            if (data.months_to_goal < 0) {
                monthsSpan.textContent = 'Not achievable with current savings';
                dateSpan.textContent = 'You need to increase income or reduce expenses';
            } else if (data.months_to_goal === 0) {
                monthsSpan.textContent = 'Goal already reached!';
                dateSpan.textContent = 'Current balance: $' + data.current_balance.toLocaleString();
            } else {
                monthsSpan.textContent = data.months_to_goal + ' months';
                dateSpan.textContent = 'Projected date: ' + data.projected_date;
            }
        });
}
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
