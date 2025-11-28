<?php 
$pageTitle = 'Password Manager'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-key me-2"></i>Password Manager</h2>
        <a href="/passwords/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Password
        </a>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-shield-lock me-2"></i>
        Your passwords are encrypted and stored securely. Click the eye icon to reveal a password.
    </div>

    <?php if (count($passwords) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>URL</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($passwords as $password): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($password['service_name']) ?></strong></td>
                            <td><?= Security::sanitizeOutput($password['username'] ?: '-') ?></td>
                            <td>
                                <span class="password-hidden" id="pwd-<?= $password['id'] ?>">••••••••</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="togglePassword(<?= $password['id'] ?>)">
                                    <i class="bi bi-eye" id="eye-<?= $password['id'] ?>"></i>
                                </button>
                            </td>
                            <td>
                                <?php if ($password['url']): ?>
                                <a href="<?= Security::sanitizeOutput($password['url']) ?>" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-box-arrow-up-right"></i> Visit
                                </a>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-secondary"><?= Security::sanitizeOutput($password['category'] ?: 'General') ?></span></td>
                            <td>
                                <a href="/passwords/edit/<?= $password['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/passwords/delete/<?= $password['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this password?')">
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
            <i class="bi bi-key display-1 text-muted"></i>
            <h4 class="mt-3">No passwords saved</h4>
            <p class="text-muted">Start storing your passwords securely.</p>
            <a href="/passwords/create" class="btn btn-primary">Add Your First Password</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const csrfToken = '<?= Security::generateCSRFToken() ?>';
const passwordCache = {};

function togglePassword(id) {
    const pwdSpan = document.getElementById('pwd-' + id);
    const eyeIcon = document.getElementById('eye-' + id);
    
    if (pwdSpan.textContent === '••••••••') {
        if (passwordCache[id]) {
            pwdSpan.textContent = passwordCache[id];
            eyeIcon.className = 'bi bi-eye-slash';
        } else {
            fetch('/passwords/view/' + id + '?csrf_token=' + csrfToken)
                .then(response => response.json())
                .then(data => {
                    if (data.password) {
                        passwordCache[id] = data.password;
                        pwdSpan.textContent = data.password;
                        eyeIcon.className = 'bi bi-eye-slash';
                    }
                });
        }
    } else {
        pwdSpan.textContent = '••••••••';
        eyeIcon.className = 'bi bi-eye';
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
