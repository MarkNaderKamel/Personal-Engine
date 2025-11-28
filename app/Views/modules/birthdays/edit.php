<?php 
$pageTitle = 'Edit Birthday'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Birthday</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/birthdays/edit/<?= $birthday['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Person's Name *</label>
                            <input type="text" name="person_name" class="form-control" value="<?= Security::sanitizeOutput($birthday['person_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-control" value="<?= $birthday['birth_date'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Relationship</label>
                            <select name="relationship" class="form-select">
                                <option value="">Select...</option>
                                <option value="Family" <?= ($birthday['relationship'] ?? '') === 'Family' ? 'selected' : '' ?>>Family</option>
                                <option value="Friend" <?= ($birthday['relationship'] ?? '') === 'Friend' ? 'selected' : '' ?>>Friend</option>
                                <option value="Colleague" <?= ($birthday['relationship'] ?? '') === 'Colleague' ? 'selected' : '' ?>>Colleague</option>
                                <option value="Partner" <?= ($birthday['relationship'] ?? '') === 'Partner' ? 'selected' : '' ?>>Partner</option>
                                <option value="Other" <?= ($birthday['relationship'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remind me</label>
                            <select name="reminder_days" class="form-select">
                                <option value="1" <?= $birthday['reminder_days'] == 1 ? 'selected' : '' ?>>1 day before</option>
                                <option value="3" <?= $birthday['reminder_days'] == 3 ? 'selected' : '' ?>>3 days before</option>
                                <option value="7" <?= $birthday['reminder_days'] == 7 ? 'selected' : '' ?>>1 week before</option>
                                <option value="14" <?= $birthday['reminder_days'] == 14 ? 'selected' : '' ?>>2 weeks before</option>
                                <option value="30" <?= $birthday['reminder_days'] == 30 ? 'selected' : '' ?>>1 month before</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gift Ideas</label>
                            <textarea name="gift_ideas" class="form-control" rows="2"><?= Security::sanitizeOutput($birthday['gift_ideas'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"><?= Security::sanitizeOutput($birthday['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Birthday
                            </button>
                            <a href="/birthdays" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
