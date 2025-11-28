<?php 
$pageTitle = 'Dashboard'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <h2>Dashboard</h2>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Level & XP</h6>
                    <?php if ($userStats): ?>
                    <h3>Level <?= $userStats['level'] ?></h3>
                    <p><?= $userStats['total_xp'] ?> XP</p>
                    <small>Streak: <?= $userStats['current_streak'] ?> days</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Tasks</h6>
                    <?php if ($taskStats): ?>
                    <h3><?= $taskStats['pending'] ?></h3>
                    <p>Pending Tasks</p>
                    <small><?= $taskStats['completed'] ?> completed</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Bills</h6>
                    <h3><?= count($upcomingBills) ?></h3>
                    <p>Upcoming Bills</p>
                    <small><?= count($overdueBills) ?> overdue</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Subscriptions</h6>
                    <h3>$<?= number_format($monthlySubscriptionTotal, 2) ?></h3>
                    <p>Monthly Total</p>
                    <small><?= count($activeSubscriptions) ?> active</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Upcoming Bills (Next 7 Days)</h5>
                </div>
                <div class="card-body">
                    <?php if (count($upcomingBills) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($upcomingBills, 0, 5) as $bill): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><?= Security::sanitizeOutput($bill['bill_name']) ?></span>
                                <span class="text-danger">$<?= number_format($bill['amount'], 2) ?></span>
                            </div>
                            <small class="text-muted">Due: <?= date('M d, Y', strtotime($bill['due_date'])) ?></small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted">No upcoming bills</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pending Tasks</h5>
                </div>
                <div class="card-body">
                    <?php if (count($pendingTasks) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach (array_slice($pendingTasks, 0, 5) as $task): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><?= Security::sanitizeOutput($task['title']) ?></span>
                                <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                                    <?= $task['priority'] ?>
                                </span>
                            </div>
                            <?php if ($task['due_date']): ?>
                            <small class="text-muted">Due: <?= date('M d, Y', strtotime($task['due_date'])) ?></small>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted">No pending tasks</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent XP Activity</h5>
                </div>
                <div class="card-body">
                    <?php if (count($recentXP) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($recentXP as $xp): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span><?= Security::sanitizeOutput($xp['description']) ?></span>
                                <span class="badge bg-primary">+<?= $xp['xp_earned'] ?> XP</span>
                            </div>
                            <small class="text-muted"><?= date('M d, Y H:i', strtotime($xp['created_at'])) ?></small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted">No recent activity</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
