<?php 
$pageTitle = 'Tasks'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Task Management</h2>
        <a href="/tasks/create" class="btn btn-primary">Add New Task</a>
    </div>
    
    <?php if (count($tasks) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                <tr>
                    <td>
                        <strong><?= Security::sanitizeOutput($task['title']) ?></strong>
                        <?php if ($task['description']): ?>
                        <br><small class="text-muted"><?= Security::sanitizeOutput(substr($task['description'], 0, 100)) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                            <?= $task['priority'] ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-<?= $task['status'] == 'completed' ? 'success' : 'info' ?>">
                            <?= $task['status'] ?>
                        </span>
                    </td>
                    <td><?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A' ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/tasks/edit/<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($task['status'] != 'completed'): ?>
                            <form method="POST" action="/tasks/complete/<?= $task['id'] ?>" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Complete">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="/tasks/delete/<?= $task['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No tasks found. <a href="/tasks/create">Add your first task</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
