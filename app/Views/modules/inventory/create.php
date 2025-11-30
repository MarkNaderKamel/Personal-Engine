<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Inventory Item</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/inventory/create" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Item Name *</label>
                                <input type="text" class="form-control" name="item_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Appliances">Appliances</option>
                                    <option value="Jewelry">Jewelry</option>
                                    <option value="Art">Art</option>
                                    <option value="Clothing">Clothing</option>
                                    <option value="Sports">Sports Equipment</option>
                                    <option value="Tools">Tools</option>
                                    <option value="Musical">Musical Instruments</option>
                                    <option value="Collectibles">Collectibles</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Model Number</label>
                                <input type="text" class="form-control" name="model_number">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Serial Number</label>
                                <input type="text" class="form-control" name="serial_number">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Room</label>
                                <select class="form-select" name="room">
                                    <option value="">Select...</option>
                                    <option value="Living Room">Living Room</option>
                                    <option value="Bedroom">Bedroom</option>
                                    <option value="Kitchen">Kitchen</option>
                                    <option value="Bathroom">Bathroom</option>
                                    <option value="Office">Office</option>
                                    <option value="Garage">Garage</option>
                                    <option value="Basement">Basement</option>
                                    <option value="Attic">Attic</option>
                                    <option value="Storage">Storage</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specific Location</label>
                                <input type="text" class="form-control" name="location" placeholder="e.g., Top shelf, under bed">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Purchase Price ($)</label>
                                <input type="number" class="form-control" name="purchase_price" step="0.01" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Current Value ($)</label>
                                <input type="number" class="form-control" name="current_value" step="0.01" min="0">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Warranty Expiry</label>
                                <input type="date" class="form-control" name="warranty_expiry">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Condition</label>
                                <select class="form-select" name="condition">
                                    <option value="excellent">Excellent</option>
                                    <option value="good" selected>Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_insured" id="isInsured">
                                    <label class="form-check-label" for="isInsured">Item is Insured</label>
                                </div>
                            </div>

                            <div class="col-md-6" id="insuranceValue" style="display: none;">
                                <label class="form-label">Insurance Value ($)</label>
                                <input type="number" class="form-control" name="insurance_value" step="0.01" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Photo</label>
                                <input type="file" class="form-control" name="photo" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Receipt/Documents</label>
                                <input type="file" class="form-control" name="receipt" accept="image/*,application/pdf">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Item
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
