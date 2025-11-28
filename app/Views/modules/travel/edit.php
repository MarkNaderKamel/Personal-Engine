<?php 
$pageTitle = 'Edit Trip'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Trip</h5>
                </div>
                <div class="card-body">
                    <form action="/travel/edit/<?= $trip['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="destination" class="form-label">Destination *</label>
                                <input type="text" class="form-control" id="destination" name="destination" value="<?= Security::sanitizeOutput($trip['destination']) ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="planned" <?= $trip['status'] === 'planned' ? 'selected' : '' ?>>Planned</option>
                                    <option value="ongoing" <?= $trip['status'] === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                                    <option value="completed" <?= $trip['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $trip['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $trip['start_date'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $trip['end_date'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget" class="form-label">Budget</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="budget" name="budget" value="<?= $trip['budget'] ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="accommodation" class="form-label">Accommodation</label>
                                <input type="text" class="form-control" id="accommodation" name="accommodation" value="<?= Security::sanitizeOutput($trip['accommodation']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="transportation" class="form-label">Transportation</label>
                                <input type="text" class="form-control" id="transportation" name="transportation" value="<?= Security::sanitizeOutput($trip['transportation']) ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes & Itinerary</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4"><?= Security::sanitizeOutput($trip['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/travel" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Trip</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
