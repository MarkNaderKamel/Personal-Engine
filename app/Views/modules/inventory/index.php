<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-house-gear me-2"></i>Home Inventory</h1>
            <p class="text-muted mb-0">Track and manage your home belongings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/inventory/export" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i>Export CSV
            </a>
            <a href="/inventory/create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Add Item
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Total Items</p>
                            <h3 class="mb-0"><?= number_format($totalValue['total_items'] ?? 0) ?></h3>
                        </div>
                        <i class="bi bi-box-seam display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Current Value</p>
                            <h3 class="mb-0">$<?= number_format($totalValue['total_value'] ?? 0, 0) ?></h3>
                        </div>
                        <i class="bi bi-cash-coin display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Purchase Total</p>
                            <h3 class="mb-0">$<?= number_format($totalValue['total_purchase'] ?? 0, 0) ?></h3>
                        </div>
                        <i class="bi bi-receipt display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Insured Value</p>
                            <h3 class="mb-0">$<?= number_format($totalValue['total_insured'] ?? 0, 0) ?></h3>
                        </div>
                        <i class="bi bi-shield-check display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($expiringWarranties)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong><?= count($expiringWarranties) ?> warranties expiring soon!</strong>
            <?php foreach (array_slice($expiringWarranties, 0, 3) as $item): ?>
                - <?= htmlspecialchars($item['item_name']) ?> (<?= date('M j', strtotime($item['warranty_expiry'])) ?>)
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Items</h5>
                    <input type="text" class="form-control form-control-sm" id="searchItems" 
                           style="max-width: 250px;" placeholder="Search items...">
                </div>
                <div class="card-body">
                    <?php if (empty($items)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-box display-1 text-muted"></i>
                            <h4 class="mt-3">No Items Yet</h4>
                            <p class="text-muted">Start tracking your home inventory</p>
                            <a href="/inventory/create" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Add First Item
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="inventoryTable">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Room</th>
                                        <th class="text-end">Value</th>
                                        <th>Warranty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if ($item['photo_path']): ?>
                                                        <img src="/<?= $item['photo_path'] ?>" class="rounded me-2" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                                        <?php if ($item['brand']): ?>
                                                            <small class="d-block text-muted"><?= htmlspecialchars($item['brand']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category'] ?? 'Uncategorized') ?></span></td>
                                            <td><?= htmlspecialchars($item['room'] ?? '-') ?></td>
                                            <td class="text-end">
                                                <strong>$<?= number_format($item['current_value'] ?? 0, 0) ?></strong>
                                            </td>
                                            <td>
                                                <?php if ($item['warranty_expiry']): ?>
                                                    <?php $expiry = strtotime($item['warranty_expiry']); ?>
                                                    <span class="badge <?= $expiry < time() ? 'bg-danger' : ($expiry < strtotime('+60 days') ? 'bg-warning' : 'bg-success') ?>">
                                                        <?= date('M j, Y', $expiry) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/inventory/view/<?= $item['id'] ?>" class="btn btn-outline-info" title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-outline-primary" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/inventory/delete/<?= $item['id'] ?>" 
                                                          style="display: inline;" onsubmit="return confirm('Delete this item?')">
                                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
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
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-pie-chart me-2"></i>By Category</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($categorySummary)): ?>
                        <?php foreach ($categorySummary as $cat): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= htmlspecialchars($cat['category'] ?? 'Other') ?></span>
                                <span class="badge bg-primary"><?= $cat['item_count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No categories yet</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-geo-alt me-2"></i>By Room</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($roomSummary)): ?>
                        <?php foreach ($roomSummary as $room): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= htmlspecialchars($room['room'] ?? 'Other') ?></span>
                                <span class="badge bg-info"><?= $room['item_count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No rooms yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchItems').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#inventoryTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
