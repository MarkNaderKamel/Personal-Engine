<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Life Atlas - Create your account">
    <meta name="theme-color" content="#667eea">
    <title>Create Account - Life Atlas</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card" style="max-width: 500px;">
            <div class="login-logo">
                <i class="bi bi-globe-americas"></i>
            </div>
            <h1 class="login-title">Life Atlas</h1>
            <p class="login-subtitle">Start organizing your life today</p>
            
            <h4 class="text-center mb-4 fw-bold">Create Your Account</h4>
            
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); endif; ?>
            
            <form method="POST" action="/register">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                   placeholder="John" value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                   placeholder="Doe" value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="you@example.com" required
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Create a strong password" required minlength="8">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="form-text">Must be at least 8 characters</div>
                </div>
                
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" 
                               placeholder="Confirm your password" required>
                    </div>
                </div>
                
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label small" for="terms">
                        I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> 
                        and <a href="#" class="text-decoration-none">Privacy Policy</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="bi bi-person-plus me-2"></i>Create Account
                </button>
            </form>
            
            <div class="divider">or</div>
            
            <div class="text-center">
                <p class="mb-0">Already have an account? <a href="/login" class="text-decoration-none fw-bold text-primary">Sign In</a></p>
            </div>
            
            <div class="mt-4 pt-4 border-top">
                <h6 class="text-center text-muted mb-3">What you'll get:</h6>
                <div class="row g-2 text-center">
                    <div class="col-6 col-md-3">
                        <i class="bi bi-check2-square text-success fs-5"></i>
                        <small class="d-block text-muted">Task Manager</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <i class="bi bi-wallet2 text-primary fs-5"></i>
                        <small class="d-block text-muted">Finance Tools</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <i class="bi bi-robot text-info fs-5"></i>
                        <small class="d-block text-muted">AI Assistant</small>
                    </div>
                    <div class="col-6 col-md-3">
                        <i class="bi bi-graph-up text-warning fs-5"></i>
                        <small class="d-block text-muted">Analytics</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
        
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>
