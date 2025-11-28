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
<body class="<?= \App\Core\Security::isAuthenticated() ? 'has-sidebar' : '' ?>">
    <?php if (\App\Core\Security::isAuthenticated()): ?>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="/dashboard" class="sidebar-brand">
                <i class="bi bi-globe-americas"></i>
                <span>Life Atlas</span>
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <nav class="sidebar-nav">
                <!-- Dashboard -->
                <a href="/dashboard" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0 ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- Financial Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Financial</div>
                    <a href="/transactions" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/transactions') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-cash-stack"></i>
                        <span>Transactions</span>
                    </a>
                    <a href="/bills" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/bills') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-receipt"></i>
                        <span>Bills</span>
                    </a>
                    <a href="/budgets" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/budgets') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-pie-chart"></i>
                        <span>Budgets</span>
                    </a>
                    <a href="/subscriptions" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/subscriptions') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-arrow-repeat"></i>
                        <span>Subscriptions</span>
                    </a>
                    <a href="/debts" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/debts') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Debts</span>
                    </a>
                    <a href="/assets" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/assets') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-building"></i>
                        <span>Assets</span>
                    </a>
                    <a href="/crypto" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/crypto') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-currency-bitcoin"></i>
                        <span>Crypto Portfolio</span>
                    </a>
                </div>
                
                <!-- Career Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Career</div>
                    <a href="/jobs" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/jobs') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-briefcase"></i>
                        <span>Job Applications</span>
                    </a>
                    <a href="/resume" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/resume') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-file-person"></i>
                        <span>CV Manager</span>
                    </a>
                    <a href="/goals" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/goals') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-bullseye"></i>
                        <span>Goals</span>
                    </a>
                </div>
                
                <!-- Productivity Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Productivity</div>
                    <a href="/tasks" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/tasks') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-list-task"></i>
                        <span>Tasks</span>
                    </a>
                    <a href="/projects" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/projects') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-kanban"></i>
                        <span>Projects</span>
                    </a>
                    <a href="/time-tracking" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/time-tracking') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-stopwatch"></i>
                        <span>Time Tracking</span>
                    </a>
                    <a href="/habits" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/habits') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-check2-all"></i>
                        <span>Habits</span>
                    </a>
                    <a href="/notes" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/notes') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-journal-text"></i>
                        <span>Notes</span>
                    </a>
                    <a href="/events" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/events') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-calendar-event"></i>
                        <span>Events</span>
                    </a>
                    <a href="/contracts" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/contracts') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Contracts</span>
                    </a>
                </div>
                
                <!-- Personal Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Personal</div>
                    <a href="/contacts" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/contacts') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Contacts</span>
                    </a>
                    <a href="/relationships" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/relationships') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Relationships</span>
                    </a>
                    <a href="/birthdays" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/birthdays') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-balloon"></i>
                        <span>Birthdays</span>
                    </a>
                    <a href="/pets" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/pets') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-heart"></i>
                        <span>Pets</span>
                    </a>
                    <a href="/reading" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/reading') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-book"></i>
                        <span>Reading List</span>
                    </a>
                    <a href="/travel" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/travel') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-airplane"></i>
                        <span>Travel</span>
                    </a>
                    <a href="/vehicles" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/vehicles') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-car-front"></i>
                        <span>Vehicles</span>
                    </a>
                    <a href="/social-links" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/social-links') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-share"></i>
                        <span>Social Links</span>
                    </a>
                </div>
                
                <!-- Tools Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Tools</div>
                    <a href="/ai-assistant" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/ai-assistant') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-robot"></i>
                        <span>AI Assistant</span>
                    </a>
                    <a href="/passwords" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/passwords') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-key"></i>
                        <span>Passwords</span>
                    </a>
                    <a href="/documents" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/documents') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-folder"></i>
                        <span>Documents</span>
                    </a>
                    <a href="/weather" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/weather') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-cloud-sun"></i>
                        <span>Weather</span>
                    </a>
                    <a href="/news" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/news') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-newspaper"></i>
                        <span>News</span>
                    </a>
                    <a href="/analytics" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/analytics') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-graph-up"></i>
                        <span>Analytics</span>
                    </a>
                </div>
                
                <?php if (\App\Core\Security::isAdmin()): ?>
                <!-- Admin Section -->
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Admin</div>
                    <a href="/admin" class="sidebar-link <?= $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '' ?>">
                        <i class="bi bi-shield-lock"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/admin/users" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-people-fill"></i>
                        <span>Users</span>
                    </a>
                    <a href="/admin/logs" class="sidebar-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/logs') === 0 ? 'active' : '' ?>">
                        <i class="bi bi-journal-text"></i>
                        <span>Logs</span>
                    </a>
                </div>
                <?php endif; ?>
            </nav>
        </div>
        
        <!-- Sidebar Footer - User Stats -->
        <div class="sidebar-footer">
            <?php
            $gamification = new \App\Models\Gamification();
            $stats = $gamification->getUserStats($_SESSION['user_id'] ?? 0);
            ?>
            <div class="sidebar-stats">
                <div class="stat-item">
                    <span class="stat-value"><?= $stats['level'] ?? 1 ?></span>
                    <span class="stat-label">Level</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= $stats['current_streak'] ?? 0 ?></span>
                    <span class="stat-label">Streak</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?= number_format($stats['total_xp'] ?? 0) ?></span>
                    <span class="stat-label">XP</span>
                </div>
            </div>
        </div>
    </aside>
    
    <!-- Sidebar Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Top Header -->
    <header class="top-header">
        <div class="header-left">
            <button class="sidebar-toggle-btn" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <div class="header-breadcrumb d-none d-md-block">
                <span class="text-muted"><?= $pageTitle ?? 'Dashboard' ?></span>
            </div>
        </div>
        
        <div class="header-right">
            <!-- Notifications -->
            <a href="/notifications" class="header-icon-btn position-relative">
                <i class="bi bi-bell"></i>
                <span class="notification-badge d-none" id="notificationCount"></span>
            </a>
            
            <!-- User Menu -->
            <div class="dropdown">
                <button class="user-menu-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <span class="user-name d-none d-md-inline"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Account') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                    <li class="dropdown-header">
                        <small class="text-muted">Signed in as</small>
                        <div class="fw-bold"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/profile"><i class="bi bi-gear me-2"></i>Profile Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="/logout">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="main-content <?= \App\Core\Security::isAuthenticated() ? '' : 'no-sidebar' ?>">
        <div class="content-wrapper">
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
