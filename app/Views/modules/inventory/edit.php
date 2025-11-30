<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Inventory Item</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/inventory/edit/<?= $item['id'] ?>" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Item Name *</label>
                                <input type="text" class="form-control" name="item_name" 
                                       value="<?= htmlspecialchars($item['item_name']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <?php 
                                    $categories = ['Electronics', 'Furniture', 'Appliances', 'Jewelry', 'Art', 'Clothing', 'Sports', 'Tools', 'Musical', 'Collectibles', 'Other'];
                                    foreach ($categories as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $item['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand" value="<?= htmlspecialchars($item['brand'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Model Number</label>
                                <input type="text" class="form-control" name="model_number" value="<?= htmlspecialchars($item['model_number'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" name="serial_number" value="<?= htmlspecialchars($item['serial_number'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Room</label>
                                <select class="form-select" name="room">
                                    <option value="">Select...</option>
                                    <?php 
                                    $rooms = ['Living Room', 'Bedroom', 'Kitchen', 'Bathroom', 'Office', 'Garage', 'Basement', 'Attic', 'Storage', 'Other'];
                                    foreach ($rooms as $room): ?>
                                        <option value="<?= $room ?>" <?= $item['room'] === $room ? 'selected' : '' ?>><?= $room ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specific Location</label>
                                <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($item['location'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" value="<?= $item['purchase_date'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Purchase Price ($)</label>
                                <input type="number" class="form-control" name="purchase_price" step="0.01" min="0" value="<?= $item['purchase_price'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Current Value ($)</label>
                                <input type="number" class="form-control" name="current_value" step="0.01" min="0" value="<?= $item['current_value'] ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Warranty Expiry</label>
                                <input type="date" class="form-control" name="warranty_expiry" value="<?= $item['warranty_expiry'] ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Condition</label>
                                <select class="form-select" name="condition">
                                    <option value="excellent" <?= $item['condition'] === 'excellent' ? 'selected' : '' ?>>Excellent</option>
                                    <option value="good" <?= $item['condition'] === 'good' ? 'selected' : '' ?>>Good</option>
                                    <option value="fair" <?= $item['condition'] === 'fair' ? 'selected' : '' ?>>Fair</option>
                                    <option value="poor" <?= $item['condition'] === 'poor' ? 'selected' : '' ?>>Poor</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_insured" id="isInsured" 
                                           <?= $item['is_insured'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="isInsured">Item is Insured</label>
                                </div>
                            </div>

                            <div class="col-md-6" id="insuranceValue" style="<?= $item['is_insured'] ? '' : 'display: none;' ?>">
                                <label class="form-label">Insurance Value ($)</label>
                                <input type="number" class="form-control" name="insurance_value" step="0.01" min="0" value="<?= $item['insurance_value'] ?>">
                            </div>

                            <?php if ($item['photo_path']): ?>
                                <div class="col-12">
                                    <label class="form-label">Current Photo</label>
                                    <div><img src="/<?= $item['photo_path'] ?>" class="img-thumbnail" style="max-height: 150px;"></div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6">
                                <label class="form-label">Update Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Update Receipt/Documents</label>
                                <input type="file" class="form-control" name="receipt" accept="image/*,application/pdf">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3"><?= htmlspecialchars($item['notes'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Item
                            </button>
                            <a href="/inventory" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('isInsured').addEventListener('change', function() {
    document.getElementById('insuranceValue').style.display = this.checked ? 'block' : 'none';
});
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
