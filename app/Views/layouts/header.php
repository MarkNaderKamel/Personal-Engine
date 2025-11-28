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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/dashboard">Life Atlas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Financial
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/bills">Bills</a></li>
                            <li><a class="dropdown-item" href="/budgets">Budgets</a></li>
                            <li><a class="dropdown-item" href="/subscriptions">Subscriptions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Productivity
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/tasks">Tasks</a></li>
                            <li><a class="dropdown-item" href="/projects">Projects</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/ai-assistant">AI Assistant</a></li>
                    <li class="nav-item"><a class="nav-link" href="/documents">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="/notifications">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger" id="notificationCount"></span>
                    </a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (\App\Core\Security::isAdmin()): ?>
                    <li class="nav-item"><a class="nav-link" href="/admin">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
                    <li class="nav-item">
                        <form method="POST" action="/logout" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="nav-link btn btn-link" style="border:none; padding:var(--bs-nav-link-padding-y) var(--bs-nav-link-padding-x);">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    
    <div class="container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); endif; ?>
