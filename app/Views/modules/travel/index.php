<?php 
$pageTitle = 'Travel Planner'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-airplane me-2"></i>Travel Planner</h2>
        <a href="/travel/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Plan Trip
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6><i class="bi bi-calendar-check me-2"></i>Upcoming Trips</h6>
                    <h3><?= count($upcomingTrips) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6><i class="bi bi-wallet2 me-2"></i>Total Planned Budget</h6>
                    <h3>$<?= number_format($totalBudget, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($upcomingTrips) > 0): ?>
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Upcoming Adventures</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($upcomingTrips as $trip): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body">
                            <h5><?= Security::sanitizeOutput($trip['destination']) ?></h5>
                            <p class="text-muted mb-1">
                                <i class="bi bi-calendar me-1"></i>
                                <?= date('M d', strtotime($trip['start_date'])) ?> - <?= date('M d, Y', strtotime($trip['end_date'])) ?>
                            </p>
                            <?php if ($trip['budget']): ?>
                            <p class="mb-0"><i class="bi bi-wallet me-1"></i>Budget: $<?= number_format($trip['budget'], 2) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($trips) > 0): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">All Trips</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Destination</th>
                            <th>Dates</th>
                            <th>Budget</th>
                            <th>Accommodation</th>
                            <th>Transportation</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trips as $trip): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($trip['destination']) ?></strong></td>
                            <td>
                                <?php if ($trip['start_date'] && $trip['end_date']): ?>
                                    <?= date('M d', strtotime($trip['start_date'])) ?> - <?= date('M d, Y', strtotime($trip['end_date'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $trip['budget'] ? '$' . number_format($trip['budget'], 2) : '-' ?></td>
                            <td><?= Security::sanitizeOutput($trip['accommodation'] ?: '-') ?></td>
                            <td><?= Security::sanitizeOutput($trip['transportation'] ?: '-') ?></td>
                            <td>
                                <span class="badge bg-<?= $trip['status'] === 'completed' ? 'success' : ($trip['status'] === 'ongoing' ? 'warning' : 'primary') ?>">
                                    <?= ucfirst($trip['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/travel/edit/<?= $trip['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/travel/delete/<?= $trip['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this trip?')">
                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-airplane display-1 text-muted"></i>
            <h4 class="mt-3">No trips planned</h4>
            <p class="text-muted">Start planning your next adventure!</p>
            <a href="/travel/create" class="btn btn-primary">Plan Your First Trip</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
