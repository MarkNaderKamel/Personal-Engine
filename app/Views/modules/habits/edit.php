<?php 
$pageTitle = 'Edit Habit'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Habit</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/habits/edit/<?= $habit['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Habit Name *</label>
                            <input type="text" name="habit_name" class="form-control" value="<?= Security::sanitizeOutput($habit['habit_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"><?= Security::sanitizeOutput($habit['description'] ?? '') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Frequency</label>
                                <select name="frequency" class="form-select">
                                    <option value="daily" <?= $habit['frequency'] === 'daily' ? 'selected' : '' ?>>Daily</option>
                                    <option value="weekly" <?= $habit['frequency'] === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                                    <option value="weekdays" <?= $habit['frequency'] === 'weekdays' ? 'selected' : '' ?>>Weekdays Only</option>
                                    <option value="weekends" <?= $habit['frequency'] === 'weekends' ? 'selected' : '' ?>>Weekends Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Count per Day</label>
                                <input type="number" name="target_count" class="form-control" value="<?= $habit['target_count'] ?? 1 ?>" min="1" max="10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Health" <?= ($habit['category'] ?? '') === 'Health' ? 'selected' : '' ?>>Health & Fitness</option>
                                    <option value="Productivity" <?= ($habit['category'] ?? '') === 'Productivity' ? 'selected' : '' ?>>Productivity</option>
                                    <option value="Learning" <?= ($habit['category'] ?? '') === 'Learning' ? 'selected' : '' ?>>Learning</option>
                                    <option value="Mindfulness" <?= ($habit['category'] ?? '') === 'Mindfulness' ? 'selected' : '' ?>>Mindfulness</option>
                                    <option value="Social" <?= ($habit['category'] ?? '') === 'Social' ? 'selected' : '' ?>>Social</option>
                                    <option value="Finance" <?= ($habit['category'] ?? '') === 'Finance' ? 'selected' : '' ?>>Finance</option>
                                    <option value="Other" <?= ($habit['category'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color</label>
                                <input type="color" name="color" class="form-control form-control-color w-100" value="<?= $habit['color'] ?? '#667eea' ?>">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" <?= $habit['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Habit
                            </button>
                            <a href="/habits" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
