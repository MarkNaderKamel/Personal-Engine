<?php 
$pageTitle = 'Edit Vehicle'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Vehicle</h5>
                </div>
                <div class="card-body">
                    <form action="/vehicles/edit/<?= $vehicle['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">Year *</label>
                                <input type="number" class="form-control" id="year" name="year" value="<?= $vehicle['year'] ?>" min="1900" max="2030" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="make" class="form-label">Make *</label>
                                <input type="text" class="form-control" id="make" name="make" value="<?= Security::sanitizeOutput($vehicle['make']) ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="model" class="form-label">Model *</label>
                                <input type="text" class="form-control" id="model" name="model" value="<?= Security::sanitizeOutput($vehicle['model']) ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="license_plate" class="form-label">License Plate</label>
                                <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?= Security::sanitizeOutput($vehicle['license_plate']) ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="next_service" class="form-label">Next Service Date</label>
                                <input type="date" class="form-control" id="next_service" name="next_service" value="<?= $vehicle['next_service'] ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insurance_expiry" class="form-label">Insurance Expiry</label>
                                <input type="date" class="form-control" id="insurance_expiry" name="insurance_expiry" value="<?= $vehicle['insurance_expiry'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($vehicle['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/vehicles" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
