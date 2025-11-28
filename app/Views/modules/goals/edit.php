<?php 
$pageTitle = 'Edit Goal'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Goal</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/goals/edit/<?= $goal['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Goal Title *</label>
                            <input type="text" name="goal_title" class="form-control" value="<?= Security::sanitizeOutput($goal['goal_title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"><?= Security::sanitizeOutput($goal['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Personal" <?= ($goal['category'] ?? '') === 'Personal' ? 'selected' : '' ?>>Personal</option>
                                    <option value="Career" <?= ($goal['category'] ?? '') === 'Career' ? 'selected' : '' ?>>Career</option>
                                    <option value="Health" <?= ($goal['category'] ?? '') === 'Health' ? 'selected' : '' ?>>Health & Fitness</option>
                                    <option value="Financial" <?= ($goal['category'] ?? '') === 'Financial' ? 'selected' : '' ?>>Financial</option>
                                    <option value="Education" <?= ($goal['category'] ?? '') === 'Education' ? 'selected' : '' ?>>Education</option>
                                    <option value="Relationships" <?= ($goal['category'] ?? '') === 'Relationships' ? 'selected' : '' ?>>Relationships</option>
                                    <option value="Travel" <?= ($goal['category'] ?? '') === 'Travel' ? 'selected' : '' ?>>Travel</option>
                                    <option value="Other" <?= ($goal['category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="low" <?= ($goal['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                                    <option value="medium" <?= ($goal['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="high" <?= ($goal['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="in_progress" <?= $goal['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="completed" <?= $goal['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="paused" <?= $goal['status'] === 'paused' ? 'selected' : '' ?>>Paused</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $goal['start_date'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Target Date</label>
                                <input type="date" name="target_date" class="form-control" value="<?= $goal['target_date'] ?? '' ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Progress: <?= $goal['progress'] ?>%</label>
                                <input type="range" name="progress" class="form-range mt-2" min="0" max="100" value="<?= $goal['progress'] ?>">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Goal
                            </button>
                            <a href="/goals" class="btn btn-outline-secondary">Cancel</a>
                            <form method="POST" action="/goals/delete/<?= $goal['id'] ?>" class="ms-auto" onsubmit="return confirm('Delete this goal?')">
                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-outline-danger">Delete Goal</button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-flag me-2"></i>Milestones</h6>
                    <a href="/goals/milestone/<?= $goal['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($milestones) > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($milestones as $ms): ?>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <div>
                                <?php if ($ms['is_completed']): ?>
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-decoration-line-through"><?= Security::sanitizeOutput($ms['milestone_title']) ?></span>
                                <?php else: ?>
                                <i class="bi bi-circle text-muted me-2"></i>
                                <?= Security::sanitizeOutput($ms['milestone_title']) ?>
                                <?php endif; ?>
                            </div>
                            <?php if (!$ms['is_completed']): ?>
                            <form method="POST" action="/goals/complete-milestone/<?= $ms['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                <input type="hidden" name="goal_id" value="<?= $goal['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Complete">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No milestones yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
