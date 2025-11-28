<?php 
$pageTitle = 'Relationships'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people-fill me-2"></i>Relationships</h2>
        <a href="/relationships/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Relationship
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (count($relationships) > 0): ?>
            <div class="row">
                <?php foreach ($relationships as $rel): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle p-3 me-3">
                                    <i class="bi bi-person-heart"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0"><?= Security::sanitizeOutput($rel['person_name']) ?></h5>
                                    <span class="badge bg-secondary"><?= Security::sanitizeOutput($rel['relationship_type'] ?? 'Unspecified') ?></span>
                                </div>
                            </div>
                            
                            <?php if ($rel['start_date']): ?>
                            <p class="mb-1">
                                <i class="bi bi-calendar-heart me-2 text-muted"></i>
                                <small>Since: <?= date('M d, Y', strtotime($rel['start_date'])) ?></small>
                            </p>
                            <?php endif; ?>
                            
                            <?php if ($rel['important_dates']): ?>
                            <p class="mb-1">
                                <i class="bi bi-star me-2 text-muted"></i>
                                <small><?= Security::sanitizeOutput($rel['important_dates']) ?></small>
                            </p>
                            <?php endif; ?>
                            
                            <?php if ($rel['notes']): ?>
                            <p class="text-muted small mt-2"><?= Security::sanitizeOutput(substr($rel['notes'], 0, 100)) ?><?= strlen($rel['notes']) > 100 ? '...' : '' ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group btn-group-sm w-100">
                                <a href="/relationships/edit/<?= $rel['id'] ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                <form method="POST" action="/relationships/delete/<?= $rel['id'] ?>" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <h5>No Relationships Added</h5>
                <p class="text-muted">Keep track of important people in your life</p>
                <a href="/relationships/create" class="btn btn-primary">Add Your First Relationship</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
