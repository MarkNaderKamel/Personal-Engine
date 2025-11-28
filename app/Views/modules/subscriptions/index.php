<?php 
$pageTitle = 'Subscriptions'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Subscription Management</h2>
        <a href="/subscriptions/create" class="btn btn-primary">Add Subscription</a>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Monthly Total</h5>
                    <h3>$<?= number_format($monthlyTotal, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (count($subscriptions) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Cost</th>
                    <th>Billing Cycle</th>
                    <th>Next Billing</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $sub): ?>
                <tr>
                    <td><?= Security::sanitizeOutput($sub['service_name']) ?></td>
                    <td>$<?= number_format($sub['cost'], 2) ?></td>
                    <td><?= Security::sanitizeOutput($sub['billing_cycle']) ?></td>
                    <td><?= date('M d, Y', strtotime($sub['next_billing_date'])) ?></td>
                    <td>
                        <span class="badge bg-<?= $sub['status'] == 'active' ? 'success' : 'secondary' ?>">
                            <?= $sub['status'] ?>
                        </span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/subscriptions/edit/<?= $sub['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="/subscriptions/toggle-status/<?= $sub['id'] ?>" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-<?= $sub['status'] == 'active' ? 'warning' : 'success' ?>" 
                                        title="<?= $sub['status'] == 'active' ? 'Cancel' : 'Reactivate' ?>">
                                    <i class="bi bi-<?= $sub['status'] == 'active' ? 'pause' : 'play' ?>"></i>
                                </button>
                            </form>
                            <form method="POST" action="/subscriptions/delete/<?= $sub['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No subscriptions found. <a href="/subscriptions/create">Add your first subscription</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
