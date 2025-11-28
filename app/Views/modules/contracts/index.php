<?php 
$pageTitle = 'Contracts'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-text me-2"></i>Contracts</h2>
        <a href="/contracts/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Contract
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6><i class="bi bi-file-earmark-check me-2"></i>Active Contracts</h6>
                    <h3><?= count($activeContracts) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Expiring Soon</h6>
                    <h3><?= count($expiringContracts) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6><i class="bi bi-currency-dollar me-2"></i>Total Value</h6>
                    <h3>$<?= number_format($totalValue, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($expiringContracts) > 0): ?>
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong><?= count($expiringContracts) ?> contract(s) expiring in the next 30 days!</strong>
    </div>
    <?php endif; ?>

    <?php if (count($contracts) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Contract Name</th>
                            <th>Party</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contracts as $contract): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($contract['contract_name']) ?></strong></td>
                            <td><?= Security::sanitizeOutput($contract['party_name'] ?: '-') ?></td>
                            <td><?= $contract['start_date'] ? date('M d, Y', strtotime($contract['start_date'])) : '-' ?></td>
                            <td>
                                <?php if ($contract['end_date']): ?>
                                    <?php 
                                    $endDate = strtotime($contract['end_date']);
                                    $daysLeft = ceil(($endDate - time()) / 86400);
                                    $class = $daysLeft <= 30 ? 'text-danger' : '';
                                    ?>
                                    <span class="<?= $class ?>"><?= date('M d, Y', $endDate) ?></span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>$<?= number_format($contract['value'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $contract['status'] === 'active' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($contract['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/contracts/edit/<?= $contract['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/contracts/delete/<?= $contract['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this contract?')">
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
            <i class="bi bi-file-earmark-text display-1 text-muted"></i>
            <h4 class="mt-3">No contracts yet</h4>
            <p class="text-muted">Start tracking your contracts and agreements.</p>
            <a href="/contracts/create" class="btn btn-primary">Add Contract</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
