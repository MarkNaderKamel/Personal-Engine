<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - Life Atlas</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="login-container" style="flex-direction: column;">
        <div class="text-center animate-fadeInUp">
            <div class="mb-4">
                <i class="bi bi-compass text-primary animate-float" style="font-size: 8rem; opacity: 0.3;"></i>
            </div>
            <h1 class="display-1 fw-bold text-gradient mb-3">404</h1>
            <h2 class="mb-3">Page Not Found</h2>
            <p class="text-muted mb-4" style="max-width: 400px;">
                Oops! The page you're looking for seems to have wandered off. 
                Let's get you back on track.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="/dashboard" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>Go Home
                </a>
                <button onclick="history.back()" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-arrow-left me-2"></i>Go Back
                </button>
            </div>
            
            <div class="mt-5 pt-4">
                <p class="text-muted small">Looking for something specific?</p>
                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <a href="/tasks" class="btn btn-sm btn-outline-secondary">Tasks</a>
                    <a href="/bills" class="btn btn-sm btn-outline-secondary">Bills</a>
                    <a href="/goals" class="btn btn-sm btn-outline-secondary">Goals</a>
                    <a href="/ai-assistant" class="btn btn-sm btn-outline-secondary">AI Assistant</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
