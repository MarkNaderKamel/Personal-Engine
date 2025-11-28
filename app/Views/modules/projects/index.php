<?php 
$pageTitle = 'Projects'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Project Management</h2>
        <a href="/projects/create" class="btn btn-primary">Create Project</a>
    </div>
    
    <?php if (count($projects) > 0): ?>
    <div class="row">
        <?php foreach ($projects as $project): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= Security::sanitizeOutput($project['project_name']) ?></h5>
                    <p class="card-text"><?= Security::sanitizeOutput(substr($project['description'] ?? '', 0, 100)) ?></p>
                    <p class="mb-1">
                        <span class="badge bg-<?= $project['status'] == 'active' ? 'success' : 'secondary' ?>">
                            <?= $project['status'] ?>
                        </span>
                    </p>
                    <?php if ($project['start_date']): ?>
                    <small class="text-muted">Start: <?= date('M d, Y', strtotime($project['start_date'])) ?></small>
                    <?php endif; ?>
                    <div class="mt-3">
                        <a href="/projects/view/<?= $project['id'] ?>" class="btn btn-sm btn-primary">View</a>
                        <form method="POST" action="/projects/delete/<?= $project['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No projects found. <a href="/projects/create">Create your first project</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
