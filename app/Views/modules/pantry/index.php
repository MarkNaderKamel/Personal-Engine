<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-basket me-2"></i>Smart Pantry</h1>
            <p class="text-muted mb-0">Track your food and household items</p>
        </div>
        <a href="/pantry/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Item
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Total Items</p>
                            <h3 class="mb-0"><?= $stats['total_items'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-box-seam display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Expired</p>
                            <h3 class="mb-0"><?= $stats['expired_count'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-x-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Expiring Soon</p>
                            <h3 class="mb-0"><?= $stats['expiring_soon'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-exclamation-triangle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Low Stock</p>
                            <h3 class="mb-0"><?= $stats['low_stock_count'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-arrow-down-circle display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($expired)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-octagon me-2"></i>
            <strong><?= count($expired) ?> items have expired!</strong> Please check and dispose of them.
        </div>
    <?php endif; ?>

    <?php if (!empty($expiringSoon)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-clock me-2"></i>
            <strong><?= count($expiringSoon) ?> items expiring within 7 days:</strong>
            <?= implode(', ', array_map(fn($i) => htmlspecialchars($i['item_name']), array_slice($expiringSoon, 0, 5))) ?>
            <?= count($expiringSoon) > 5 ? '...' : '' ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($lowStock)): ?>
        <div class="alert alert-info">
            <i class="bi bi-cart me-2"></i>
            <strong>Low stock items:</strong>
            <?= implode(', ', array_map(fn($i) => htmlspecialchars($i['item_name']), array_slice($lowStock, 0, 5))) ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Pantry Items</h5>
                    <input type="text" class="form-control form-control-sm" id="searchPantry" 
                           style="max-width: 250px;" placeholder="Search items...">
                </div>
                <div class="card-body">
                    <?php if (empty($items)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-basket display-1 text-muted"></i>
                            <h4 class="mt-3">Your Pantry is Empty</h4>
                            <p class="text-muted">Start tracking your food and household items</p>
                            <a href="/pantry/create" class="btn btn-primary">
                                <i class="bi bi-plus-lg me-1"></i>Add First Item
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="pantryTable">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th class="text-center">Quantity</th>
                                        <th>Location</th>
                                        <th>Expiry</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <?php 
                                        $isExpired = $item['expiry_date'] && strtotime($item['expiry_date']) < time();
                                        $isExpiringSoon = $item['expiry_date'] && strtotime($item['expiry_date']) < strtotime('+7 days');
                                        $isLowStock = $item['quantity'] <= $item['minimum_stock'];
                                        ?>
                                        <tr class="<?= $isExpired ? 'table-danger' : ($isExpiringSoon ? 'table-warning' : '') ?>">
                                            <td>
                                                <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                                <?php if ($isLowStock): ?>
                                                    <i class="bi bi-exclamation-circle text-warning ms-1" title="Low stock"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-secondary"><?= htmlspecialchars($item['category'] ?? 'Other') ?></span></td>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-1">
                                                    <form method="POST" action="/pantry/adjust/<?= $item['id'] ?>" style="display: inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                                        <input type="hidden" name="action" value="deduct">
                                                        <input type="hidden" name="amount" value="1">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-1">-</button>
                                                    </form>
                                                    <span class="px-2 fw-bold"><?= $item['quantity'] ?> <?= $item['unit'] ?></span>
                                                    <form method="POST" action="/pantry/adjust/<?= $item['id'] ?>" style="display: inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                                        <input type="hidden" name="action" value="add">
                                                        <input type="hidden" name="amount" value="1">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary py-0 px-1">+</button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($item['location'] ?? '-') ?></td>
                                            <td>
                                                <?php if ($item['expiry_date']): ?>
                                                    <span class="badge bg-<?= $isExpired ? 'danger' : ($isExpiringSoon ? 'warning' : 'success') ?>">
                                                        <?= date('M j, Y', strtotime($item['expiry_date'])) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="/pantry/edit/<?= $item['id'] ?>" class="btn btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="/pantry/delete/<?= $item['id'] ?>" 
                                                          style="display: inline;" onsubmit="return confirm('Remove this item?')">
                                                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                                        <button type="submit" class="btn btn-outline-danger">
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
                    <h6 class="mb-0"><i class="bi bi-tag me-2"></i>Categories</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?= htmlspecialchars($cat['category'] ?? 'Other') ?></span>
                                <span class="badge bg-primary"><?= $cat['item_count'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">No categories</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Quick Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0">
                        <li>Track expiry dates to reduce waste</li>
                        <li>Set minimum stock levels</li>
                        <li>Check low stock before shopping</li>
                        <li>Use with Recipes to auto-deduct</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchPantry').addEventListener('input', function() {
    const search = this.value.toLowerCase();
    document.querySelectorAll('#pantryTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
});
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
