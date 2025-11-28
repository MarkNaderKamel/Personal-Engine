<?php 
$pageTitle = 'Pet Care'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-heart me-2"></i>Pet Care</h2>
        <a href="/pets/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Pet
        </a>
    </div>

    <?php if (count($upcomingCheckups) > 0): ?>
    <div class="alert alert-info">
        <i class="bi bi-calendar-check me-2"></i>
        <strong><?= count($upcomingCheckups) ?> pet(s) have checkups scheduled in the next 30 days!</strong>
    </div>
    <?php endif; ?>

    <?php if (count($pets) > 0): ?>
    <div class="row">
        <?php foreach ($pets as $pet): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-heart-fill me-2"></i><?= Security::sanitizeOutput($pet['pet_name']) ?></h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Type:</strong> <?= Security::sanitizeOutput($pet['pet_type'] ?: 'Not specified') ?>
                    </p>
                    <?php if ($pet['breed']): ?>
                    <p class="mb-2">
                        <strong>Breed:</strong> <?= Security::sanitizeOutput($pet['breed']) ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($pet['birthday']): ?>
                    <p class="mb-2">
                        <strong>Birthday:</strong> <?= date('M d, Y', strtotime($pet['birthday'])) ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($pet['vet_name']): ?>
                    <p class="mb-2">
                        <strong>Vet:</strong> <?= Security::sanitizeOutput($pet['vet_name']) ?>
                    </p>
                    <?php endif; ?>
                    <?php if ($pet['next_checkup']): ?>
                    <p class="mb-2">
                        <strong>Next Checkup:</strong> 
                        <span class="<?= strtotime($pet['next_checkup']) <= strtotime('+7 days') ? 'text-danger' : '' ?>">
                            <?= date('M d, Y', strtotime($pet['next_checkup'])) ?>
                        </span>
                    </p>
                    <?php endif; ?>
                    <?php if ($pet['notes']): ?>
                    <p class="text-muted small"><?= Security::sanitizeOutput($pet['notes']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group btn-group-sm w-100">
                        <a href="/pets/edit/<?= $pet['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="/pets/delete/<?= $pet['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove this pet?')">
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
            <i class="bi bi-heart display-1 text-muted"></i>
            <h4 class="mt-3">No pets added yet</h4>
            <p class="text-muted">Add your furry friends to track their care.</p>
            <a href="/pets/create" class="btn btn-primary">Add Your First Pet</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
