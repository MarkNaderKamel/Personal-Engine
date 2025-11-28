<?php 
$pageTitle = 'Goals'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$priorityColors = ['high' => 'danger', 'medium' => 'warning', 'low' => 'secondary'];
$statusColors = ['in_progress' => 'primary', 'completed' => 'success', 'paused' => 'warning'];
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-bullseye me-2"></i>Goals</h2>
            <p class="text-muted mb-0">Track and achieve your personal and professional goals</p>
        </div>
        <a href="/goals/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Goal
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-bullseye display-6 mb-2"></i>
                    <h4><?= $stats['total'] ?></h4>
                    <small>Total Goals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-arrow-repeat display-6 mb-2"></i>
                    <h4><?= $stats['active'] ?></h4>
                    <small>In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-trophy display-6 mb-2"></i>
                    <h4><?= $stats['completed'] ?></h4>
                    <small>Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card text-white h-100" style="background: var(--primary-gradient);">
                <div class="card-body stat-card">
                    <i class="bi bi-graph-up display-6 mb-2"></i>
                    <h4><?= round($stats['avg_progress'] ?? 0) ?>%</h4>
                    <small>Avg Progress</small>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($goals) > 0): ?>
    <div class="row">
        <?php foreach ($goals as $goal): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 hover-lift">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge bg-<?= $priorityColors[$goal['priority']] ?? 'secondary' ?>">
                        <?= ucfirst($goal['priority']) ?>
                    </span>
                    <span class="badge bg-<?= $statusColors[$goal['status']] ?? 'secondary' ?>">
                        <?= ucfirst(str_replace('_', ' ', $goal['status'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= Security::sanitizeOutput($goal['goal_title']) ?></h5>
                    <?php if ($goal['category']): ?>
                    <small class="text-muted"><i class="bi bi-tag me-1"></i><?= Security::sanitizeOutput($goal['category']) ?></small>
                    <?php endif; ?>
                    
                    <?php if ($goal['description']): ?>
                    <p class="card-text mt-2 small"><?= Security::sanitizeOutput(substr($goal['description'], 0, 100)) ?><?= strlen($goal['description']) > 100 ? '...' : '' ?></p>
                    <?php endif; ?>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted">Progress</small>
                            <small class="fw-bold"><?= $goal['progress'] ?>%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: <?= $goal['progress'] ?>%"></div>
                        </div>
                    </div>

                    <?php if ($goal['target_date']): ?>
                    <div class="mt-3">
                        <small class="text-muted"><i class="bi bi-calendar me-1"></i>Target: <?= date('M d, Y', strtotime($goal['target_date'])) ?></small>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex gap-2">
                        <a href="/goals/view/<?= $goal['id'] ?>" class="btn btn-sm btn-outline-info">View</a>
                        <a href="/goals/edit/<?= $goal['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <?php if ($goal['status'] !== 'completed'): ?>
                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#progressModal<?= $goal['id'] ?>">
                            Update
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="progressModal<?= $goal['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <form method="POST" action="/goals/progress/<?= $goal['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <div class="modal-header">
                                <h6 class="modal-title">Update Progress</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Progress: <span id="progressValue<?= $goal['id'] ?>"><?= $goal['progress'] ?></span>%</label>
                                <input type="range" name="progress" class="form-range" min="0" max="100" value="<?= $goal['progress'] ?>" 
                                    oninput="document.getElementById('progressValue<?= $goal['id'] ?>').textContent = this.value">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                            </div>
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
            <i class="bi bi-bullseye text-muted"></i>
            <h5>No goals yet</h5>
            <p class="text-muted">Set your first goal and start tracking your progress!</p>
            <a href="/goals/create" class="btn btn-primary">Create Your First Goal</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
