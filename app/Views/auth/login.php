<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Life Atlas - Your All-in-One Personal Life Management Platform">
    <meta name="theme-color" content="#667eea">
    <title>Login - Life Atlas</title>
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
        <div class="login-card">
            <div class="login-logo">
                <i class="bi bi-globe-americas"></i>
            </div>
            <h1 class="login-title">Life Atlas</h1>
            <p class="login-subtitle">Your All-in-One Personal Life Manager</p>
            
            <h4 class="text-center mb-4 fw-bold">Welcome Back</h4>
            
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); endif; ?>
            
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); endif; ?>
            
            <form method="POST" action="/login">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="you@example.com" required autocomplete="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Enter your password" required autocomplete="current-password">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    <a href="/forgot-password" class="text-decoration-none small text-primary">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                </button>
            </form>
            
            <div class="divider">or</div>
            
            <div class="text-center">
                <p class="mb-0">Don't have an account? <a href="/register" class="text-decoration-none fw-bold text-primary">Create Account</a></p>
            </div>
            
            <div class="mt-4 pt-4 border-top text-center">
                <div class="row g-3">
                    <div class="col-4">
                        <div class="feature-icon bg-primary-soft mx-auto">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <small class="text-muted d-block mt-2">Secure</small>
                    </div>
                    <div class="col-4">
                        <div class="feature-icon bg-success-soft mx-auto">
                            <i class="bi bi-lightning text-success"></i>
                        </div>
                        <small class="text-muted d-block mt-2">Fast</small>
                    </div>
                    <div class="col-4">
                        <div class="feature-icon bg-info-soft mx-auto">
                            <i class="bi bi-cloud text-info"></i>
                        </div>
                        <small class="text-muted d-block mt-2">Cloud Sync</small>
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
    </script>
</body>
</html>
