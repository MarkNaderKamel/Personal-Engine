<?php 
$pageTitle = 'Add Birthday'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-balloon me-2"></i>Add Birthday</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/birthdays/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Person's Name *</label>
                            <input type="text" name="person_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Birth Date *</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Relationship</label>
                            <select name="relationship" class="form-select">
                                <option value="">Select...</option>
                                <option value="Family">Family</option>
                                <option value="Friend">Friend</option>
                                <option value="Colleague">Colleague</option>
                                <option value="Partner">Partner</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remind me</label>
                            <select name="reminder_days" class="form-select">
                                <option value="1">1 day before</option>
                                <option value="3">3 days before</option>
                                <option value="7" selected>1 week before</option>
                                <option value="14">2 weeks before</option>
                                <option value="30">1 month before</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gift Ideas</label>
                            <textarea name="gift_ideas" class="form-control" rows="2" placeholder="Ideas for gifts..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Birthday
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
