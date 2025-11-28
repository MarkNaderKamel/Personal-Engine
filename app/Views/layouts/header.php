<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Life Atlas - Your All-in-One Personal Life Management Platform">
    <meta name="theme-color" content="#667eea">
    <title><?= $pageTitle ?? 'Life Atlas' ?> - Life Atlas</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php if (\App\Core\Security::isAuthenticated()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/dashboard">
                <i class="bi bi-globe-americas me-2"></i>Life Atlas
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0 ? 'active' : '' ?>" href="/dashboard">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-wallet2 me-1"></i>Financial
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/transactions"><i class="bi bi-cash-stack me-2"></i>Transactions</a></li>
                            <li><a class="dropdown-item" href="/bills"><i class="bi bi-receipt me-2"></i>Bills</a></li>
                            <li><a class="dropdown-item" href="/budgets"><i class="bi bi-pie-chart me-2"></i>Budgets</a></li>
                            <li><a class="dropdown-item" href="/subscriptions"><i class="bi bi-arrow-repeat me-2"></i>Subscriptions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/debts"><i class="bi bi-credit-card-2-front me-2"></i>Debts</a></li>
                            <li><a class="dropdown-item" href="/assets"><i class="bi bi-building me-2"></i>Assets</a></li>
                            <li><a class="dropdown-item" href="/crypto"><i class="bi bi-currency-bitcoin me-2"></i>Crypto Portfolio</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-briefcase me-1"></i>Career
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/jobs"><i class="bi bi-briefcase me-2"></i>Job Applications</a></li>
                            <li><a class="dropdown-item" href="/resume"><i class="bi bi-file-person me-2"></i>CV Manager</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/goals"><i class="bi bi-bullseye me-2"></i>Goals</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-check2-square me-1"></i>Productivity
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/tasks"><i class="bi bi-list-task me-2"></i>Tasks</a></li>
                            <li><a class="dropdown-item" href="/projects"><i class="bi bi-kanban me-2"></i>Projects</a></li>
                            <li><a class="dropdown-item" href="/time-tracking"><i class="bi bi-stopwatch me-2"></i>Time Tracking</a></li>
                            <li><a class="dropdown-item" href="/habits"><i class="bi bi-check2-all me-2"></i>Habits</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/notes"><i class="bi bi-journal-text me-2"></i>Notes</a></li>
                            <li><a class="dropdown-item" href="/events"><i class="bi bi-calendar-event me-2"></i>Events</a></li>
                            <li><a class="dropdown-item" href="/contracts"><i class="bi bi-file-earmark-text me-2"></i>Contracts</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-heart me-1"></i>Personal
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/contacts"><i class="bi bi-people me-2"></i>Contacts</a></li>
                            <li><a class="dropdown-item" href="/relationships"><i class="bi bi-people-fill me-2"></i>Relationships</a></li>
                            <li><a class="dropdown-item" href="/birthdays"><i class="bi bi-balloon me-2"></i>Birthdays</a></li>
                            <li><a class="dropdown-item" href="/pets"><i class="bi bi-heart me-2"></i>Pets</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/reading"><i class="bi bi-book me-2"></i>Reading List</a></li>
                            <li><a class="dropdown-item" href="/travel"><i class="bi bi-airplane me-2"></i>Travel</a></li>
                            <li><a class="dropdown-item" href="/vehicles"><i class="bi bi-car-front me-2"></i>Vehicles</a></li>
                            <li><a class="dropdown-item" href="/social-links"><i class="bi bi-share me-2"></i>Social Links</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-tools me-1"></i>Tools
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="/ai-assistant"><i class="bi bi-robot me-2"></i>AI Assistant</a></li>
                            <li><a class="dropdown-item" href="/passwords"><i class="bi bi-key me-2"></i>Passwords</a></li>
                            <li><a class="dropdown-item" href="/documents"><i class="bi bi-folder me-2"></i>Documents</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/weather"><i class="bi bi-cloud-sun me-2"></i>Weather</a></li>
                            <li><a class="dropdown-item" href="/news"><i class="bi bi-newspaper me-2"></i>News</a></li>
                            <li><a class="dropdown-item" href="/analytics"><i class="bi bi-graph-up me-2"></i>Analytics</a></li>
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
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <i class="bi bi-person"></i>
                            </div>
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Account') ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                            <li class="px-3 py-2 border-bottom border-secondary">
                                <small class="text-muted">Signed in as</small>
                                <div class="fw-bold"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                            </li>
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout">
                                    <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <main class="<?= \App\Core\Security::isAuthenticated() ? 'py-4' : '' ?>">
        <div class="container-fluid">
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div><?= htmlspecialchars($_SESSION['success']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); endif; ?>
            
            <?php if (isset($_SESSION['warning'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div><?= htmlspecialchars($_SESSION['warning']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['warning']); endif; ?>
            
            <?php if (isset($_SESSION['info'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div><?= htmlspecialchars($_SESSION['info']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['info']); endif; ?>
