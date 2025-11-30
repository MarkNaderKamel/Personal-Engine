<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add Pantry Item</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/pantry/create">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="mb-3">
                            <label class="form-label">Item Name *</label>
                            <input type="text" class="form-control" name="item_name" required autofocus>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <option value="Fruits">Fruits</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Dairy">Dairy</option>
                                    <option value="Meat">Meat</option>
                                    <option value="Grains">Grains & Bread</option>
                                    <option value="Canned">Canned Goods</option>
                                    <option value="Frozen">Frozen Foods</option>
                                    <option value="Beverages">Beverages</option>
                                    <option value="Snacks">Snacks</option>
                                    <option value="Condiments">Condiments</option>
                                    <option value="Spices">Spices & Seasonings</option>
                                    <option value="Baking">Baking Supplies</option>
                                    <option value="Cleaning">Cleaning Supplies</option>
                                    <option value="Personal">Personal Care</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" value="1" min="0" step="0.1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Unit</label>
                                <select class="form-select" name="unit">
                                    <option value="piece">Piece</option>
                                    <option value="kg">Kg</option>
                                    <option value="g">Grams</option>
                                    <option value="L">Liters</option>
                                    <option value="ml">ml</option>
                                    <option value="pack">Pack</option>
                                    <option value="box">Box</option>
                                    <option value="can">Can</option>
                                    <option value="bottle">Bottle</option>
                                    <option value="bag">Bag</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" name="purchase_date" value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <select class="form-select" name="location">
                                    <option value="">Select...</option>
                                    <option value="Fridge">Refrigerator</option>
                                    <option value="Freezer">Freezer</option>
                                    <option value="Pantry">Pantry</option>
                                    <option value="Cabinet">Cabinet</option>
                                    <option value="Counter">Counter</option>
                                    <option value="Storage">Storage Room</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minimum Stock Alert</label>
                                <input type="number" class="form-control" name="minimum_stock" value="1" min="0" step="0.1">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" class="form-control" name="purchase_price" step="0.01" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" placeholder="Optional">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Item
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
