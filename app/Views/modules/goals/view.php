<?php 
$pageTitle = 'Goal Details'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$statusColors = ['in_progress' => 'primary', 'completed' => 'success', 'paused' => 'warning'];
?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/goals">Goals</a></li>
            <li class="breadcrumb-item active"><?= Security::sanitizeOutput($goal['goal_title']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= Security::sanitizeOutput($goal['goal_title']) ?></h4>
                    <span class="badge bg-<?= $statusColors[$goal['status']] ?? 'secondary' ?> fs-6">
                        <?= ucfirst(str_replace('_', ' ', $goal['status'])) ?>
                    </span>
                </div>
                <div class="card-body">
                    <?php if ($goal['category']): ?>
                    <p class="mb-3"><span class="badge bg-primary-soft"><?= Security::sanitizeOutput($goal['category']) ?></span></p>
                    <?php endif; ?>

                    <?php if ($goal['description']): ?>
                    <p><?= nl2br(Security::sanitizeOutput($goal['description'])) ?></p>
                    <?php endif; ?>

                    <div class="mt-4">
                        <h6>Progress</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Current Progress</span>
                            <strong><?= $goal['progress'] ?>%</strong>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" style="width: <?= $goal['progress'] ?>%"><?= $goal['progress'] ?>%</div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <p><strong>Start Date:</strong> <?= $goal['start_date'] ? date('F d, Y', strtotime($goal['start_date'])) : 'Not set' ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Target Date:</strong> <?= $goal['target_date'] ? date('F d, Y', strtotime($goal['target_date'])) : 'Not set' ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/goals/edit/<?= $goal['id'] ?>" class="btn btn-primary">Edit Goal</a>
                    <a href="/goals" class="btn btn-outline-secondary">Back to Goals</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-flag me-2"></i>Milestones</h6>
                    <a href="/goals/milestone/<?= $goal['id'] ?>" class="btn btn-sm btn-primary">Add</a>
                </div>
                <div class="card-body">
                    <?php if (count($milestones) > 0): ?>
                    <?php 
                    $completed = array_filter($milestones, fn($m) => $m['is_completed']);
                    $progress = count($milestones) > 0 ? round((count($completed) / count($milestones)) * 100) : 0;
                    ?>
                    <div class="mb-3">
                        <small class="text-muted"><?= count($completed) ?> of <?= count($milestones) ?> completed</small>
                        <div class="progress mt-1" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: <?= $progress ?>%"></div>
                        </div>
                    </div>
                    <ul class="list-unstyled">
                        <?php foreach ($milestones as $ms): ?>
                        <li class="mb-2">
                            <?php if ($ms['is_completed']): ?>
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                            <span class="text-decoration-line-through text-muted"><?= Security::sanitizeOutput($ms['milestone_title']) ?></span>
                            <?php else: ?>
                            <i class="bi bi-circle text-muted me-2"></i>
                            <?= Security::sanitizeOutput($ms['milestone_title']) ?>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted text-center">Break down your goal into smaller milestones</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
