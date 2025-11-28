<?php 
$pageTitle = 'Create Habit'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check2-square me-2"></i>Create New Habit</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/habits/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Habit Name *</label>
                            <input type="text" name="habit_name" class="form-control" placeholder="e.g., Exercise for 30 minutes" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Why is this habit important to you?"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Frequency</label>
                                <select name="frequency" class="form-select">
                                    <option value="daily" selected>Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="weekdays">Weekdays Only</option>
                                    <option value="weekends">Weekends Only</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Target Count per Day</label>
                                <input type="number" name="target_count" class="form-control" value="1" min="1" max="10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="Health">Health & Fitness</option>
                                    <option value="Productivity">Productivity</option>
                                    <option value="Learning">Learning</option>
                                    <option value="Mindfulness">Mindfulness</option>
                                    <option value="Social">Social</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Color</label>
                                <input type="color" name="color" class="form-control form-control-color w-100" value="#667eea">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Create Habit
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
