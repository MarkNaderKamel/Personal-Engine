<?php $pageTitle = 'Login'; include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-globe-americas display-1 text-primary"></i>
                <h2 class="mt-2">Life Atlas</h2>
                <p class="text-muted">Your All-in-One Personal Life Manager</p>
            </div>
            <div class="card">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4">Welcome Back</h4>
                    <form method="POST" action="/login">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required autocomplete="email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mb-3">
                            <a href="/forgot-password" class="text-decoration-none small">Forgot Password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>
                    <hr class="my-4">
                    <div class="text-center">
                        <p class="mb-0">Don't have an account? <a href="/register" class="text-decoration-none fw-bold">Create Account</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
