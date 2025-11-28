<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Life Atlas' ?> - Life Atlas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php if (\App\Core\Security::isAuthenticated()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/dashboard">
                <i class="bi bi-globe-americas me-2"></i>Life Atlas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-wallet2 me-1"></i>Financial
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/bills"><i class="bi bi-receipt me-2"></i>Bills</a></li>
                            <li><a class="dropdown-item" href="/budgets"><i class="bi bi-pie-chart me-2"></i>Budgets</a></li>
                            <li><a class="dropdown-item" href="/subscriptions"><i class="bi bi-arrow-repeat me-2"></i>Subscriptions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/debts"><i class="bi bi-credit-card-2-front me-2"></i>Debts</a></li>
                            <li><a class="dropdown-item" href="/assets"><i class="bi bi-building me-2"></i>Assets</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-check2-square me-1"></i>Productivity
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/tasks"><i class="bi bi-list-task me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item" href="/projects"><i class="bi bi-kanban me-2"></i>Projects</a></li>
                            <li><a class="dropdown-item" href="/time-tracking"><i class="bi bi-stopwatch me-2"></i>Time Tracking</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/notes"><i class="bi bi-journal-text me-2"></i>Notes</a></li>
                            <li><a class="dropdown-item" href="/events"><i class="bi bi-calendar-event me-2"></i>Events</a></li>
                            <li><a class="dropdown-item" href="/contracts"><i class="bi bi-file-earmark-text me-2"></i>Contracts</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-heart me-1"></i>Personal
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/contacts"><i class="bi bi-people me-2"></i>Contacts</a></li>
                            <li><a class="dropdown-item" href="/pets"><i class="bi bi-heart me-2"></i>Pets</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/reading"><i class="bi bi-book me-2"></i>Reading List</a></li>
                            <li><a class="dropdown-item" href="/travel"><i class="bi bi-airplane me-2"></i>Travel</a></li>
                            <li><a class="dropdown-item" href="/vehicles"><i class="bi bi-car-front me-2"></i>Vehicles</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-tools me-1"></i>Tools
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/ai-assistant"><i class="bi bi-robot me-2"></i>AI Assistant</a></li>
                            <li><a class="dropdown-item" href="/passwords"><i class="bi bi-key me-2"></i>Passwords</a></li>
                            <li><a class="dropdown-item" href="/documents"><i class="bi bi-folder me-2"></i>Documents</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/notifications">
                            <i class="bi bi-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notificationCount"></span>
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (\App\Core\Security::isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin"><i class="bi bi-shield-lock me-1"></i>Admin</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['user_name'] ?? 'Account') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                    <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <div class="container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>
