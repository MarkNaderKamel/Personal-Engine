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

    <?php if (count($data['todayBirthdays']) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex align-items-center">
                <i class="bi bi-cake2 fs-3 me-3"></i>
                <div>
                    <strong>Birthday Alert!</strong>
                    <?php foreach ($data['todayBirthdays'] as $bday): ?>
                    <span class="ms-2"><?= Security::sanitizeOutput($bday['person_name']) ?> is celebrating today!</span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-briefcase me-2 text-primary"></i>Job Applications</h5>
                    <a href="/jobs" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <h3 class="mb-0 text-primary"><?= $data['jobStats']['total'] ?? 0 ?></h3>
                            <small class="text-muted">Total</small>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0 text-warning"><?= $data['jobStats']['interviewing'] ?? 0 ?></h3>
                            <small class="text-muted">Interviewing</small>
                        </div>
                        <div class="col-4">
                            <h3 class="mb-0 text-success"><?= $data['jobStats']['offered'] ?? 0 ?></h3>
                            <small class="text-muted">Offers</small>
                        </div>
                    </div>
                    <a href="/jobs/create" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-lg me-1"></i>Add Application
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-bullseye me-2 text-success"></i>Goals Progress</h5>
                    <a href="/goals" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['activeGoals']) > 0): ?>
                    <?php foreach (array_slice($data['activeGoals'], 0, 3) as $goal): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-medium"><?= Security::sanitizeOutput($goal['goal_title']) ?></small>
                            <small class="text-muted"><?= $goal['progress'] ?>%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?= $goal['progress'] ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-bullseye text-success display-4 opacity-50"></i>
                        <p class="text-muted mt-2 mb-2">No active goals</p>
                        <a href="/goals/create" class="btn btn-sm btn-success">Set a Goal</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-check2-all me-2 text-info"></i>Today's Habits</h5>
                    <a href="/habits" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['habits']) > 0): ?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Progress</span>
                        <span class="fw-bold"><?= $data['habitStats']['completed_today'] ?? 0 ?>/<?= $data['habitStats']['active_habits'] ?? 0 ?> completed</span>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <?php $habitProgress = $data['habitStats']['active_habits'] > 0 ? ($data['habitStats']['completed_today'] / $data['habitStats']['active_habits']) * 100 : 0; ?>
                        <div class="progress-bar bg-info" style="width: <?= $habitProgress ?>%"></div>
                    </div>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['habits'], 0, 3) as $habit): ?>
                        <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                            <span><?= Security::sanitizeOutput($habit['habit_name']) ?></span>
                            <?php if ($habit['today_completed'] > 0): ?>
                            <span class="badge bg-success"><i class="bi bi-check"></i></span>
                            <?php else: ?>
                            <form method="POST" action="/habits/log/<?= $habit['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success">Done</button>
                            </form>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-check2-all text-info display-4 opacity-50"></i>
                        <p class="text-muted mt-2 mb-2">No habits tracked</p>
                        <a href="/habits/create" class="btn btn-sm btn-info">Add Habit</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-balloon me-2 text-danger"></i>Upcoming Birthdays</h5>
                    <a href="/birthdays" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (count($data['upcomingBirthdays']) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['upcomingBirthdays'], 0, 5) as $bday): 
                            $nextBirthday = isset($bday['next_birthday']) ? strtotime($bday['next_birthday']) : null;
                            $daysUntil = $nextBirthday ? floor(($nextBirthday - time()) / 86400) : null;
                        ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= Security::sanitizeOutput($bday['person_name']) ?></strong>
                                <?php if ($bday['relationship']): ?>
                                <br><small class="text-muted"><?= Security::sanitizeOutput($bday['relationship']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $daysUntil !== null && $daysUntil <= 7 ? 'warning' : 'secondary' ?>">
                                    <?= date('M d', strtotime($bday['birth_date'])) ?>
                                </span>
                                <?php if ($daysUntil !== null && $daysUntil == 0): ?>
                                <br><small class="text-success">Today!</small>
                                <?php elseif ($daysUntil !== null && $daysUntil <= 7): ?>
                                <br><small class="text-warning">In <?= $daysUntil ?> days</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-balloon text-danger display-4 opacity-50"></i>
                        <p class="text-muted mt-2 mb-2">No birthdays tracked</p>
                        <a href="/birthdays/create" class="btn btn-sm btn-danger">Add Birthday</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
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
                                <a href="/jobs/create" class="btn btn-outline-light btn-sm"><i class="bi bi-briefcase me-1"></i>New Job</a>
                                <a href="/goals/create" class="btn btn-outline-light btn-sm"><i class="bi bi-bullseye me-1"></i>New Goal</a>
                                <a href="/habits/create" class="btn btn-outline-light btn-sm"><i class="bi bi-check2-all me-1"></i>New Habit</a>
                                <a href="/resume" class="btn btn-outline-light btn-sm"><i class="bi bi-file-person me-1"></i>CV Manager</a>
                                <a href="/analytics" class="btn btn-outline-light btn-sm"><i class="bi bi-graph-up me-1"></i>Analytics</a>
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
