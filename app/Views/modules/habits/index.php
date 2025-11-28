<?php 
$pageTitle = 'Habits'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-check2-square me-2"></i>Habit Tracker</h2>
            <p class="text-muted mb-0">Build better habits, one day at a time</p>
        </div>
        <a href="/habits/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Habit
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-collection display-6 mb-2"></i>
                    <h4><?= $stats['active_habits'] ?></h4>
                    <small>Active Habits</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-check-circle display-6 mb-2"></i>
                    <h4><?= $stats['completed_today'] ?></h4>
                    <small>Completed Today</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white h-100" style="background: var(--primary-gradient);">
                <div class="card-body stat-card">
                    <i class="bi bi-graph-up-arrow display-6 mb-2"></i>
                    <h4><?= $stats['active_habits'] > 0 ? round(($stats['completed_today'] / $stats['active_habits']) * 100) : 0 ?>%</h4>
                    <small>Today's Progress</small>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($habits) > 0): ?>
    <div class="row">
        <?php foreach ($habits as $habit): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 hover-lift" style="border-left: 4px solid <?= $habit['color'] ?? '#667eea' ?>;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title mb-0"><?= Security::sanitizeOutput($habit['habit_name']) ?></h5>
                        <?php if ($habit['today_completed'] > 0): ?>
                        <span class="badge bg-success"><i class="bi bi-check"></i> Done</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($habit['description']): ?>
                    <p class="card-text small text-muted"><?= Security::sanitizeOutput($habit['description']) ?></p>
                    <?php endif; ?>

                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-secondary"><?= ucfirst($habit['frequency']) ?></span>
                        <?php if ($habit['category']): ?>
                        <span class="badge bg-primary-soft"><?= Security::sanitizeOutput($habit['category']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">This Week: <?= $habit['week_count'] ?? 0 ?>/7</small>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar" style="width: <?= (($habit['week_count'] ?? 0) / 7) * 100 ?>%; background-color: <?= $habit['color'] ?? '#667eea' ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <?php if ($habit['today_completed'] == 0): ?>
                        <form method="POST" action="/habits/log/<?= $habit['id'] ?>" style="flex: 1;">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="bi bi-check-lg me-1"></i>Complete
                            </button>
                        </form>
                        <?php else: ?>
                        <button class="btn btn-outline-success btn-sm flex-fill" disabled>
                            <i class="bi bi-check-circle-fill me-1"></i>Completed
                        </button>
                        <?php endif; ?>
                        <a href="/habits/edit/<?= $habit['id'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="/habits/delete/<?= $habit['id'] ?>" onsubmit="return confirm('Delete this habit?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body empty-state">
            <i class="bi bi-check2-square text-muted"></i>
            <h5>No habits yet</h5>
            <p class="text-muted">Start building better habits today!</p>
            <a href="/habits/create" class="btn btn-primary">Create Your First Habit</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
