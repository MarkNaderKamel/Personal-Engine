<?php 
$pageTitle = 'Add Password'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Add New Password</h5>
                </div>
                <div class="card-body">
                    <form action="/passwords/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="service_name" class="form-label">Service Name *</label>
                                <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Gmail, Facebook, etc." required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select category</option>
                                    <option value="social">Social Media</option>
                                    <option value="email">Email</option>
                                    <option value="banking">Banking</option>
                                    <option value="shopping">Shopping</option>
                                    <option value="work">Work</option>
                                    <option value="entertainment">Entertainment</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username / Email</label>
                                <input type="text" class="form-control" id="username" name="username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                        <i class="bi bi-eye" id="toggle-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-primary" type="button" onclick="generatePassword()">
                                        Generate
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="url" name="url" placeholder="https://example.com">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Security questions, recovery info..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/passwords" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Password</button>
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

function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
    let password = '';
    for (let i = 0; i < 16; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('password').value = password;
    document.getElementById('password').type = 'text';
    document.getElementById('toggle-eye').className = 'bi bi-eye-slash';
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
