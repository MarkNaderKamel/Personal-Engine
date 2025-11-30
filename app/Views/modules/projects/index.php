<?php 
$pageTitle = 'Projects'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-folder2-open me-2"></i>Project Management</h1>
            <p class="text-muted mb-0">Organize and track your projects with Kanban boards</p>
        </div>
        <a href="/projects/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Create Project
        </a>
    </div>
    
    <?php if (count($projects) > 0): ?>
    <div class="row g-4">
        <?php foreach ($projects as $project): ?>
        <div class="col-lg-6">
            <div class="card h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="bi bi-folder me-2 text-primary"></i>
                                <?= Security::sanitizeOutput($project['project_name']) ?>
                            </h5>
                            <span class="badge bg-<?= $project['status'] == 'active' ? 'success' : ($project['status'] == 'on_hold' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
                            </span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary rounded-circle" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/projects/view/<?= $project['id'] ?>"><i class="bi bi-list-ul me-2"></i>List View</a></li>
                                <li><a class="dropdown-item" href="/projects/board/<?= $project['id'] ?>"><i class="bi bi-kanban me-2"></i>Board View</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="/projects/delete/<?= $project['id'] ?>" onsubmit="return confirm('Are you sure you want to delete this project?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <?php if ($project['description']): ?>
                    <p class="card-text text-muted mb-3"><?= Security::sanitizeOutput(substr($project['description'], 0, 120)) ?><?= strlen($project['description']) > 120 ? '...' : '' ?></p>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                        <?php if ($project['start_date']): ?>
                        <span><i class="bi bi-calendar me-1"></i>Started: <?= date('M d, Y', strtotime($project['start_date'])) ?></span>
                        <?php else: ?>
                        <span><i class="bi bi-calendar me-1"></i>No start date</span>
                        <?php endif; ?>
                        <?php if ($project['end_date']): ?>
                        <span><i class="bi bi-flag me-1"></i>Due: <?= date('M d, Y', strtotime($project['end_date'])) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="/projects/view/<?= $project['id'] ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-list-ul me-1"></i>List View
                        </a>
                        <a href="/projects/board/<?= $project['id'] ?>" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="bi bi-kanban me-1"></i>Board View
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder-plus display-1 text-muted opacity-25 mb-3"></i>
            <h5 class="text-muted">No projects yet</h5>
            <p class="text-muted mb-4">Create your first project to start organizing tasks with Kanban boards</p>
            <a href="/projects/create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Create Your First Project
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
