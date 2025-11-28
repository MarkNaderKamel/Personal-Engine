<?php 
$pageTitle = 'Project Details'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2><?= Security::sanitizeOutput($project['project_name']) ?></h2>
    
    <div class="card mt-4">
        <div class="card-body">
            <h5>Project Details</h5>
            <p><?= Security::sanitizeOutput($project['description'] ?? 'No description') ?></p>
            <p><strong>Status:</strong> 
                <span class="badge bg-<?= $project['status'] == 'active' ? 'success' : 'secondary' ?>">
                    <?= $project['status'] ?>
                </span>
            </p>
            <?php if ($project['start_date']): ?>
            <p><strong>Start Date:</strong> <?= date('M d, Y', strtotime($project['start_date'])) ?></p>
            <?php endif; ?>
            <?php if ($project['end_date']): ?>
            <p><strong>End Date:</strong> <?= date('M d, Y', strtotime($project['end_date'])) ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5>Project Tasks</h5>
        </div>
        <div class="card-body">
            <?php if (count($tasks) > 0): ?>
            <ul class="list-group">
                <?php foreach ($tasks as $task): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <span><?= Security::sanitizeOutput($task['title']) ?></span>
                        <span class="badge bg-<?= $task['status'] == 'completed' ? 'success' : 'info' ?>">
                            <?= $task['status'] ?>
                        </span>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-muted">No tasks for this project yet</p>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="/projects" class="btn btn-secondary">Back to Projects</a>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
