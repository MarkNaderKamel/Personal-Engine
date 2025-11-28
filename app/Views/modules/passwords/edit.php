<?php 
$pageTitle = 'Edit Password'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Password</h5>
                </div>
                <div class="card-body">
                    <form action="/passwords/edit/<?= $password['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_name" class="form-label">Service Name *</label>
                                <input type="text" class="form-control" id="service_name" name="service_name" value="<?= Security::sanitizeOutput($password['service_name']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select category</option>
                                    <option value="social" <?= $password['category'] === 'social' ? 'selected' : '' ?>>Social Media</option>
                                    <option value="email" <?= $password['category'] === 'email' ? 'selected' : '' ?>>Email</option>
                                    <option value="banking" <?= $password['category'] === 'banking' ? 'selected' : '' ?>>Banking</option>
                                    <option value="shopping" <?= $password['category'] === 'shopping' ? 'selected' : '' ?>>Shopping</option>
                                    <option value="work" <?= $password['category'] === 'work' ? 'selected' : '' ?>>Work</option>
                                    <option value="entertainment" <?= $password['category'] === 'entertainment' ? 'selected' : '' ?>>Entertainment</option>
                                    <option value="other" <?= $password['category'] === 'other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username / Email</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= Security::sanitizeOutput($password['username']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Leave empty to keep current">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                        <i class="bi bi-eye" id="toggle-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Leave empty to keep current password</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="url" name="url" value="<?= Security::sanitizeOutput($password['url']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($password['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/passwords" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const pwd = document.getElementById('password');
    const eye = document.getElementById('toggle-eye');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        eye.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        eye.className = 'bi bi-eye';
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
