<?php 
$pageTitle = 'Analytics'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Analytics Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-currency-dollar display-6 mb-2"></i>
                    <h4>$<?= number_format($data['financialSummary']['pending_bills'], 2) ?></h4>
                    <small>Pending Bills</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-check2-circle display-6 mb-2"></i>
                    <h4><?= $data['productivityStats']['tasks_completed'] ?></h4>
                    <small>Tasks Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-kanban display-6 mb-2"></i>
                    <h4><?= $data['productivityStats']['active_projects'] ?></h4>
                    <small>Active Projects</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body stat-card">
                    <i class="bi bi-lightning display-6 mb-2"></i>
                    <h4><?= $data['xpProgress']['total_xp'] ?></h4>
                    <small>Total XP</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Monthly Bills (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="billsChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>XP Progress</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <h3 class="text-primary">Level <?= $data['xpProgress']['level'] ?></h3>
                            <small class="text-muted">Current Level</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-success"><?= $data['xpProgress']['current_streak'] ?></h3>
                            <small class="text-muted">Day Streak</small>
                        </div>
                        <div class="col-4">
                            <h3 class="text-info"><?= $data['xpProgress']['longest_streak'] ?></h3>
                            <small class="text-muted">Best Streak</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Progress to Next Level</small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <?php 
                        $xpInLevel = $data['xpProgress']['total_xp'] % 1000;
                        $progress = ($xpInLevel / 1000) * 100;
                        ?>
                        <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%">
                            <?= $xpInLevel ?> / 1000 XP
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Budget Overview</h5>
                </div>
                <div class="card-body">
                    <?php if (count($data['categoryBreakdown']['budgets']) > 0): ?>
                    <?php foreach ($data['categoryBreakdown']['budgets'] as $budget): 
                        $percent = $budget['budgeted'] > 0 ? min(100, ($budget['spent'] / $budget['budgeted']) * 100) : 0;
                        $statusClass = $percent > 90 ? 'bg-danger' : ($percent > 70 ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <strong><?= Security::sanitizeOutput($budget['category']) ?></strong>
                            <span>$<?= number_format($budget['spent'], 2) ?> / $<?= number_format($budget['budgeted'], 2) ?></span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar <?= $statusClass ?>" style="width: <?= $percent ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center">No budgets set for this month</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Bill Categories</h5>
                </div>
                <div class="card-body">
                    <?php if (count($data['categoryBreakdown']['bills']) > 0): ?>
                    <canvas id="billCategoryChart" height="200"></canvas>
                    <?php else: ?>
                    <p class="text-muted text-center">No categorized bills yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body">
                    <?php if (count($data['recentActivity']) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>XP Earned</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recentActivity'] as $activity): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= Security::sanitizeOutput($activity['action_type']) ?></span></td>
                                    <td><?= Security::sanitizeOutput($activity['description']) ?></td>
                                    <td><span class="badge bg-primary">+<?= $activity['xp_earned'] ?> XP</span></td>
                                    <td><?= date('M d, Y H:i', strtotime($activity['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center">No recent activity</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const billsData = <?= json_encode($data['monthlyBills']) ?>;
    
    if (document.getElementById('billsChart')) {
        new Chart(document.getElementById('billsChart'), {
            type: 'bar',
            data: {
                labels: billsData.map(d => d.month),
                datasets: [{
                    label: 'Bills Total',
                    data: billsData.map(d => d.total),
                    backgroundColor: 'rgba(102, 126, 234, 0.6)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    const billCategories = <?= json_encode($data['categoryBreakdown']['bills']) ?>;
    if (billCategories.length > 0 && document.getElementById('billCategoryChart')) {
        new Chart(document.getElementById('billCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: billCategories.map(c => c.category || 'Uncategorized'),
                datasets: [{
                    data: billCategories.map(c => parseFloat(c.total)),
                    backgroundColor: [
                        '#667eea', '#764ba2', '#11998e', '#38ef7d', '#f5576c',
                        '#4facfe', '#00f2fe', '#43e97b', '#fa709a', '#fee140'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
