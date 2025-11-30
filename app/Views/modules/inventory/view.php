<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i><?= htmlspecialchars($item['item_name']) ?></h5>
                    <div class="btn-group">
                        <a href="/inventory/edit/<?= $item['id'] ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="/inventory" class="btn btn-outline-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php if ($item['photo_path']): ?>
                            <div class="col-md-4 mb-4">
                                <img src="/<?= $item['photo_path'] ?>" class="img-fluid rounded shadow">
                            </div>
                        <?php endif; ?>
                        
                        <div class="col-md-<?= $item['photo_path'] ? '8' : '12' ?>">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="text-muted small">Category</label>
                                    <p class="mb-0"><span class="badge bg-primary"><?= htmlspecialchars($item['category'] ?? 'Uncategorized') ?></span></p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small">Condition</label>
                                    <p class="mb-0">
                                        <span class="badge bg-<?= $item['condition'] === 'excellent' ? 'success' : ($item['condition'] === 'good' ? 'info' : ($item['condition'] === 'fair' ? 'warning' : 'danger')) ?>">
                                            <?= ucfirst($item['condition'] ?? 'Unknown') ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small">Brand</label>
                                    <p class="mb-0 fw-bold"><?= htmlspecialchars($item['brand'] ?? '-') ?></p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small">Model Number</label>
                                    <p class="mb-0"><?= htmlspecialchars($item['model_number'] ?? '-') ?></p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small">Serial Number</label>
                                    <p class="mb-0"><code><?= htmlspecialchars($item['serial_number'] ?? '-') ?></code></p>
                                </div>
                                <div class="col-6">
                                    <label class="text-muted small">Room / Location</label>
                                    <p class="mb-0"><?= htmlspecialchars(($item['room'] ?? '') . ($item['location'] ? ' / ' . $item['location'] : '')) ?: '-' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-cart text-primary fs-2"></i>
                                    <h5 class="mt-2">$<?= number_format($item['purchase_price'] ?? 0, 2) ?></h5>
                                    <p class="text-muted mb-0">Purchase Price</p>
                                    <?php if ($item['purchase_date']): ?>
                                        <small class="text-muted"><?= date('M j, Y', strtotime($item['purchase_date'])) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-cash-coin text-success fs-2"></i>
                                    <h5 class="mt-2">$<?= number_format($item['current_value'] ?? 0, 2) ?></h5>
                                    <p class="text-muted mb-0">Current Value</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-shield-check text-warning fs-2"></i>
                                    <?php if ($item['warranty_expiry']): ?>
                                        <?php $expiry = strtotime($item['warranty_expiry']); ?>
                                        <h5 class="mt-2 <?= $expiry < time() ? 'text-danger' : '' ?>">
                                            <?= date('M j, Y', $expiry) ?>
                                        </h5>
                                        <p class="text-muted mb-0">
                                            <?= $expiry < time() ? 'Warranty Expired' : 'Warranty Valid' ?>
                                        </p>
                                    <?php else: ?>
                                        <h5 class="mt-2">-</h5>
                                        <p class="text-muted mb-0">No Warranty</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($item['is_insured']): ?>
                        <div class="alert alert-success mt-4">
                            <i class="bi bi-check-circle me-2"></i>
                            This item is insured for <strong>$<?= number_format($item['insurance_value'] ?? 0, 2) ?></strong>
                        </div>
                    <?php endif; ?>

                    <?php if ($item['notes']): ?>
                        <div class="mt-4">
                            <h6>Notes</h6>
                            <p class="text-muted mb-0"><?= nl2br(htmlspecialchars($item['notes'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($item['receipt_path']): ?>
                        <div class="mt-4">
                            <a href="/<?= $item['receipt_path'] ?>" class="btn btn-outline-primary" target="_blank">
                                <i class="bi bi-file-earmark-text me-1"></i>View Receipt/Document
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
