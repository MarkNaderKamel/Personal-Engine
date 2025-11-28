<?php 
$pageTitle = 'Add Vehicle'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Add New Vehicle</h5>
                </div>
                <div class="card-body">
                    <form action="/vehicles/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label">Year *</label>
                                <input type="number" class="form-control" id="year" name="year" min="1900" max="2030" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="make" class="form-label">Make *</label>
                                <input type="text" class="form-control" id="make" name="make" placeholder="Toyota, Ford, etc." required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="model" class="form-label">Model *</label>
                                <input type="text" class="form-control" id="model" name="model" placeholder="Camry, F-150, etc." required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="license_plate" class="form-label">License Plate</label>
                                <input type="text" class="form-control" id="license_plate" name="license_plate">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="next_service" class="form-label">Next Service Date</label>
                                <input type="date" class="form-control" id="next_service" name="next_service">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="insurance_expiry" class="form-label">Insurance Expiry</label>
                                <input type="date" class="form-control" id="insurance_expiry" name="insurance_expiry">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="VIN, color, service history..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/vehicles" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
