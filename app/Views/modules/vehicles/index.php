<?php 
$pageTitle = 'Vehicles'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-car-front me-2"></i>Vehicle Management</h2>
        <a href="/vehicles/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Vehicle
        </a>
    </div>

    <?php if (count($upcomingService) > 0 || count($expiringInsurance) > 0): ?>
    <div class="row mb-4">
        <?php if (count($upcomingService) > 0): ?>
        <div class="col-md-6">
            <div class="alert alert-warning mb-0">
                <i class="bi bi-wrench me-2"></i>
                <strong><?= count($upcomingService) ?> vehicle(s) need service soon!</strong>
            </div>
        </div>
        <?php endif; ?>
        <?php if (count($expiringInsurance) > 0): ?>
        <div class="col-md-6">
            <div class="alert alert-danger mb-0">
                <i class="bi bi-shield-exclamation me-2"></i>
                <strong><?= count($expiringInsurance) ?> vehicle(s) have expiring insurance!</strong>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (count($vehicles) > 0): ?>
    <div class="row">
        <?php foreach ($vehicles as $vehicle): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-car-front me-2"></i>
                        <?= Security::sanitizeOutput($vehicle['year']) ?> <?= Security::sanitizeOutput($vehicle['make']) ?> <?= Security::sanitizeOutput($vehicle['model']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($vehicle['license_plate']): ?>
                    <p class="mb-2">
                        <strong>License Plate:</strong> <?= Security::sanitizeOutput($vehicle['license_plate']) ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($vehicle['next_service']): ?>
                    <p class="mb-2">
                        <strong>Next Service:</strong>
                        <span class="<?= strtotime($vehicle['next_service']) <= strtotime('+7 days') ? 'text-danger' : '' ?>">
                            <?= date('M d, Y', strtotime($vehicle['next_service'])) ?>
                        </span>
                    </p>
                    <?php endif; ?>
                    <?php if ($vehicle['insurance_expiry']): ?>
                    <p class="mb-2">
                        <strong>Insurance Expires:</strong>
                        <span class="<?= strtotime($vehicle['insurance_expiry']) <= strtotime('+30 days') ? 'text-danger' : '' ?>">
                            <?= date('M d, Y', strtotime($vehicle['insurance_expiry'])) ?>
                        </span>
                    </p>
                    <?php endif; ?>
                    <?php if ($vehicle['notes']): ?>
                    <p class="text-muted small"><?= Security::sanitizeOutput($vehicle['notes']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group btn-group-sm w-100">
                        <a href="/vehicles/edit/<?= $vehicle['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="/vehicles/delete/<?= $vehicle['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove this vehicle?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-car-front display-1 text-muted"></i>
            <h4 class="mt-3">No vehicles added</h4>
            <p class="text-muted">Add your vehicles to track service and insurance.</p>
            <a href="/vehicles/create" class="btn btn-primary">Add Your First Vehicle</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
