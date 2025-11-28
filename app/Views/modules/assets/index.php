<?php 
$pageTitle = 'Assets'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-building me-2"></i>Asset Management</h2>
        <a href="/assets/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Asset
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6><i class="bi bi-gem me-2"></i>Total Asset Value</h6>
                    <h3>$<?= number_format($totalValue, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($assets) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Type</th>
                            <th>Current Value</th>
                            <th>Purchase Price</th>
                            <th>Purchase Date</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assets as $asset): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($asset['asset_name']) ?></strong></td>
                            <td><span class="badge bg-info"><?= Security::sanitizeOutput($asset['asset_type'] ?: 'Other') ?></span></td>
                            <td class="text-success fw-bold">$<?= number_format($asset['current_value'], 2) ?></td>
                            <td>$<?= number_format($asset['purchase_price'], 2) ?></td>
                            <td>
                                <?php if ($asset['purchase_date']): ?>
                                    <?= date('M d, Y', strtotime($asset['purchase_date'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= Security::sanitizeOutput($asset['location'] ?: '-') ?></td>
                            <td>
                                <a href="/assets/edit/<?= $asset['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/assets/delete/<?= $asset['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this asset?')">
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
            <i class="bi bi-building display-1 text-muted"></i>
            <h4 class="mt-3">No assets tracked</h4>
            <p class="text-muted">Start tracking your assets to monitor your net worth.</p>
            <a href="/assets/create" class="btn btn-primary">Add Your First Asset</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
