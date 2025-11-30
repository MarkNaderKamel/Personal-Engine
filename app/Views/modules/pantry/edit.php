<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Pantry Item</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/pantry/edit/<?= $item['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="mb-3">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control" name="item_name" 
                                   value="<?= htmlspecialchars($item['item_name']) ?>" required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <?php 
                                    $categories = ['Fruits', 'Vegetables', 'Dairy', 'Meat', 'Grains', 'Canned', 'Frozen', 'Beverages', 'Snacks', 'Condiments', 'Spices', 'Baking', 'Cleaning', 'Personal', 'Other'];
                                    foreach ($categories as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $item['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" 
                                       value="<?= $item['quantity'] ?>" min="0" step="0.1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unit</label>
                                <select class="form-select" name="unit">
                                    <?php 
                                    $units = ['piece', 'kg', 'g', 'L', 'ml', 'pack', 'box', 'can', 'bottle', 'bag'];
                                    foreach ($units as $unit): ?>
                                        <option value="<?= $unit ?>" <?= $item['unit'] === $unit ? 'selected' : '' ?>><?= ucfirst($unit) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date" value="<?= $item['expiry_date'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" value="<?= $item['purchase_date'] ?>">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <select class="form-select" name="location">
                                    <option value="">Select...</option>
                                    <?php 
                                    $locations = ['Fridge', 'Freezer', 'Pantry', 'Cabinet', 'Counter', 'Storage'];
                                    foreach ($locations as $loc): ?>
                                        <option value="<?= $loc ?>" <?= $item['location'] === $loc ? 'selected' : '' ?>><?= $loc ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minimum Stock Alert</label>
                                <input type="number" class="form-control" name="minimum_stock" 
                                       value="<?= $item['minimum_stock'] ?>" min="0" step="0.1">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" class="form-control" name="purchase_price" 
                                       value="<?= $item['purchase_price'] ?>" step="0.01" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" 
                                       value="<?= htmlspecialchars($item['barcode'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Item
                            </button>
                            <a href="/pantry" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
