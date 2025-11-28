<?php 
$pageTitle = 'Dashboard'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Welcome back, <?= Security::sanitizeOutput($_SESSION['user_name'] ?? 'User') ?>!</h2>
            <p class="text-muted mb-0">Here's your life overview for today</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/tasks/create" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>New Task
            </a>
            <a href="/bills/create" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i>New Bill
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50">Level & XP</h6>
                            <?php if ($data['userStats']): ?>
                            <h3 class="mb-0">Level <?= $data['userStats']['level'] ?? 1 ?></h3>
                            <p class="mb-0"><?= number_format($data['userStats']['total_xp'] ?? 0) ?> XP</p>
                            <?php else: ?>
                            <h3 class="mb-0">Level 1</h3>
                            <p class="mb-0">0 XP</p>
                            <?php endif; ?>
                        </div>
                        <i class="bi bi-trophy display-6 opacity-50"></i>
                    </div>
                    <?php if ($data['userStats']): ?>
                    <div class="mt-3">
                        <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                            <?php $xpProgress = (($data['userStats']['total_xp'] ?? 0) % 1000) / 10; ?>
                            <div class="progress-bar bg-white" style="width: <?= $xpProgress ?>%"></div>
                        </div>
                        <small class="text-white-50"><?= $data['userStats']['current_streak'] ?? 0 ?> day streak</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50">Tasks</h6>
                            <?php if ($data['taskStats']): ?>
                            <h3 class="mb-0"><?= $data['taskStats']['pending'] ?? 0 ?></h3>
                            <p class="mb-0">Pending Tasks</p>
                            <?php else: ?>
                            <h3 class="mb-0">0</h3>
                            <p class="mb-0">Pending Tasks</p>
                            <?php endif; ?>
                        </div>
                        <i class="bi bi-check2-square display-6 opacity-50"></i>
                    </div>
                    <?php if ($data['taskStats']): ?>
                    <div class="mt-2">
                        <small class="text-white-50"><?= $data['taskStats']['completed'] ?? 0 ?> completed</small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50">Bills</h6>
                            <h3 class="mb-0"><?= count($data['upcomingBills']) ?></h3>
                            <p class="mb-0">Upcoming Bills</p>
                        </div>
                        <i class="bi bi-receipt display-6 opacity-50"></i>
                    </div>
                    <div class="mt-2">
                        <?php if (count($data['overdueBills']) > 0): ?>
                        <span class="badge bg-danger"><?= count($data['overdueBills']) ?> overdue</span>
                        <?php else: ?>
                        <small class="text-white-50">All bills on track</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-white-50">Subscriptions</h6>
                            <h3 class="mb-0">$<?= number_format($data['monthlySubscriptionTotal'], 2) ?></h3>
                            <p class="mb-0">Monthly Total</p>
                        </div>
                        <i class="bi bi-arrow-repeat display-6 opacity-50"></i>
                    </div>
                    <div class="mt-2">
                        <small class="text-white-50"><?= count($data['activeSubscriptions']) ?> active subscriptions</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2 text-warning"></i>Upcoming Bills</h5>
                    <a href="/bills" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['upcomingBills']) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['upcomingBills'], 0, 5) as $bill): 
                            $dueDate = strtotime($bill['due_date']);
                            $daysUntilDue = floor(($dueDate - time()) / 86400);
                        ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><?= Security::sanitizeOutput($bill['bill_name']) ?></h6>
                                    <small class="text-muted">
                                        <?php if ($daysUntilDue < 0): ?>
                                        <span class="text-danger">Overdue by <?= abs($daysUntilDue) ?> days</span>
                                        <?php elseif ($daysUntilDue == 0): ?>
                                        <span class="text-warning">Due today</span>
                                        <?php elseif ($daysUntilDue == 1): ?>
                                        <span class="text-warning">Due tomorrow</span>
                                        <?php else: ?>
                                        Due in <?= $daysUntilDue ?> days
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="text-danger fw-bold">$<?= number_format($bill['amount'], 2) ?></span>
                                    <br><small class="text-muted"><?= date('M d', $dueDate) ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success display-4 opacity-50"></i>
                        <p class="text-muted mt-2 mb-0">No upcoming bills this week</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-task me-2 text-success"></i>Pending Tasks</h5>
                    <a href="/tasks" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['pendingTasks']) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['pendingTasks'], 0, 5) as $task): ?>
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <form method="POST" action="/tasks/complete/<?= $task['id'] ?>" class="me-2">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-circle p-1" title="Mark as complete">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <div>
                                        <h6 class="mb-0"><?= Security::sanitizeOutput($task['title']) ?></h6>
                                        <?php if ($task['due_date']): ?>
                                        <small class="text-muted">Due: <?= date('M d, Y', strtotime($task['due_date'])) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($task['priority']) ?>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-emoji-smile text-success display-4 opacity-50"></i>
                        <p class="text-muted mt-2 mb-0">All caught up! No pending tasks</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Budget Overview</h5>
                    <a href="/budgets" class="btn btn-sm btn-outline-primary">Manage Budgets</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['currentBudgets']) > 0): ?>
                    <?php foreach ($data['currentBudgets'] as $budget): 
                        $percent = $budget['budgeted_amount'] > 0 ? min(100, ($budget['spent_amount'] / $budget['budgeted_amount']) * 100) : 0;
                        $statusClass = $percent > 90 ? 'bg-danger' : ($percent > 70 ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fw-medium"><?= Security::sanitizeOutput($budget['category']) ?></span>
                            <span class="text-muted">
                                $<?= number_format($budget['spent_amount'], 2) ?> / $<?= number_format($budget['budgeted_amount'], 2) ?>
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar <?= $statusClass ?>" style="width: <?= $percent ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-pie-chart text-primary display-4 opacity-50"></i>
                        <p class="text-muted mt-2">No budgets set for this month</p>
                        <a href="/budgets/create" class="btn btn-sm btn-primary">Create Budget</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2 text-warning"></i>Recent XP Activity</h5>
                </div>
                <div class="card-body">
                    <?php if (count($data['recentXP']) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($data['recentXP'] as $xp): ?>
                        <div class="list-group-item px-0 py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small><?= Security::sanitizeOutput($xp['description']) ?></small>
                                </div>
                                <span class="badge bg-primary">+<?= $xp['xp_earned'] ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-3">
                        <p class="text-muted mb-0 small">Start using the app to earn XP!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">Quick Actions</h4>
                            <p class="mb-3 opacity-75">Navigate to your most used features</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="/ai-assistant" class="btn btn-light btn-sm"><i class="bi bi-robot me-1"></i>AI Assistant</a>
                                <a href="/notes/create" class="btn btn-outline-light btn-sm"><i class="bi bi-journal-plus me-1"></i>New Note</a>
                                <a href="/events/create" class="btn btn-outline-light btn-sm"><i class="bi bi-calendar-plus me-1"></i>New Event</a>
                                <a href="/analytics" class="btn btn-outline-light btn-sm"><i class="bi bi-graph-up me-1"></i>Analytics</a>
                                <a href="/documents" class="btn btn-outline-light btn-sm"><i class="bi bi-folder me-1"></i>Documents</a>
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <i class="bi bi-lightning-charge display-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
