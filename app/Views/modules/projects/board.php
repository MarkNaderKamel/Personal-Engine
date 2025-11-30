<?php 
$pageTitle = 'Project Board - ' . htmlspecialchars($project['project_name']); 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<style>
.kanban-board {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    overflow-x: auto;
    padding-bottom: 1rem;
}

@media (max-width: 1200px) {
    .kanban-board {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .kanban-board {
        grid-template-columns: 1fr;
    }
}

.kanban-column {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    min-height: 400px;
    display: flex;
    flex-direction: column;
}

.kanban-column-header {
    padding: 1rem 1.25rem;
    border-radius: 16px 16px 0 0;
    font-weight: 700;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.kanban-column-header .count {
    background: rgba(255, 255, 255, 0.3);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
}

.column-pending .kanban-column-header {
    background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    color: white;
}

.column-in-progress .kanban-column-header {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    color: white;
}

.column-review .kanban-column-header {
    background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
    color: white;
}

.column-completed .kanban-column-header {
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    color: white;
}

.kanban-tasks {
    flex: 1;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    min-height: 300px;
}

.kanban-task {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    cursor: grab;
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.kanban-task:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.kanban-task:active {
    cursor: grabbing;
}

.kanban-task.priority-high {
    border-left-color: #ef4444;
}

.kanban-task.priority-medium {
    border-left-color: #f59e0b;
}

.kanban-task.priority-low {
    border-left-color: #10b981;
}

.kanban-task .task-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1e293b;
}

.kanban-task .task-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: #64748b;
}

.kanban-task .task-due {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.kanban-task .task-due.overdue {
    color: #ef4444;
}

.kanban-task .task-due.soon {
    color: #f59e0b;
}

.kanban-task .task-actions {
    display: flex;
    gap: 0.5rem;
}

.kanban-task .task-actions .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.kanban-empty {
    text-align: center;
    padding: 2rem;
    color: #94a3b8;
    font-style: italic;
}

.project-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

@media (max-width: 992px) {
    .project-stats {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 576px) {
    .project-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

.stat-mini {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.stat-mini:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
}

.stat-mini .stat-number {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.stat-mini .stat-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
}

.stat-total .stat-number { color: #1e293b; }
.stat-pending .stat-number { color: #f59e0b; }
.stat-progress .stat-number { color: #3b82f6; }
.stat-review .stat-number { color: #8b5cf6; }
.stat-done .stat-number { color: #10b981; }

.progress-ring {
    width: 100%;
    background: #e2e8f0;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 1rem;
}

.progress-ring .progress-fill {
    height: 100%;
    background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.dragging {
    opacity: 0.5;
    transform: scale(1.02);
}

.drag-over {
    background: rgba(59, 130, 246, 0.1);
}
</style>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="/projects">Projects</a></li>
                    <li class="breadcrumb-item active"><?= Security::sanitizeOutput($project['project_name']) ?></li>
                </ol>
            </nav>
            <h1 class="h3 mb-1">
                <i class="bi bi-kanban me-2"></i><?= Security::sanitizeOutput($project['project_name']) ?>
            </h1>
            <?php if ($project['description']): ?>
            <p class="text-muted mb-0"><?= Security::sanitizeOutput($project['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <a href="/projects/view/<?= $project['id'] ?>" class="btn btn-outline-primary">
                <i class="bi bi-list-ul me-1"></i>List View
            </a>
            <a href="/tasks/create?project_id=<?= $project['id'] ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Add Task
            </a>
        </div>
    </div>

    <?php 
    $total = $taskStats['total'] ?? 0;
    $completed = $taskStats['completed'] ?? 0;
    $progressPercent = $total > 0 ? round(($completed / $total) * 100) : 0;
    ?>

    <div class="project-stats">
        <div class="stat-mini stat-total">
            <div class="stat-number"><?= $total ?></div>
            <div class="stat-label">Total Tasks</div>
        </div>
        <div class="stat-mini stat-pending">
            <div class="stat-number"><?= $taskStats['pending'] ?? 0 ?></div>
            <div class="stat-label">To Do</div>
        </div>
        <div class="stat-mini stat-progress">
            <div class="stat-number"><?= $taskStats['in_progress'] ?? 0 ?></div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-mini stat-review">
            <div class="stat-number"><?= $taskStats['review'] ?? 0 ?></div>
            <div class="stat-label">Review</div>
        </div>
        <div class="stat-mini stat-done">
            <div class="stat-number"><?= $completed ?></div>
            <div class="stat-label">Done</div>
            <div class="progress-ring">
                <div class="progress-fill" style="width: <?= $progressPercent ?>%"></div>
            </div>
        </div>
    </div>

    <div class="kanban-board">
        <div class="kanban-column column-pending" data-status="pending">
            <div class="kanban-column-header">
                <span><i class="bi bi-circle me-2"></i>To Do</span>
                <span class="count"><?= count($tasksByStatus['pending']) ?></span>
            </div>
            <div class="kanban-tasks" data-status="pending">
                <?php if (empty($tasksByStatus['pending'])): ?>
                    <div class="kanban-empty">No tasks</div>
                <?php else: ?>
                    <?php foreach ($tasksByStatus['pending'] as $task): ?>
                        <?php 
                        $dueClass = '';
                        if ($task['due_date']) {
                            $dueDate = strtotime($task['due_date']);
                            $daysUntil = floor(($dueDate - time()) / 86400);
                            if ($daysUntil < 0) $dueClass = 'overdue';
                            elseif ($daysUntil <= 2) $dueClass = 'soon';
                        }
                        ?>
                        <div class="kanban-task priority-<?= $task['priority'] ?? 'low' ?>" 
                             draggable="true" 
                             data-task-id="<?= $task['id'] ?>">
                            <div class="task-title"><?= Security::sanitizeOutput($task['title']) ?></div>
                            <div class="task-meta">
                                <?php if ($task['due_date']): ?>
                                <span class="task-due <?= $dueClass ?>">
                                    <i class="bi bi-calendar"></i>
                                    <?= date('M j', strtotime($task['due_date'])) ?>
                                </span>
                                <?php else: ?>
                                <span></span>
                                <?php endif; ?>
                                <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($task['priority'] ?? 'low') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="kanban-column column-in-progress" data-status="in_progress">
            <div class="kanban-column-header">
                <span><i class="bi bi-play-circle me-2"></i>In Progress</span>
                <span class="count"><?= count($tasksByStatus['in_progress']) ?></span>
            </div>
            <div class="kanban-tasks" data-status="in_progress">
                <?php if (empty($tasksByStatus['in_progress'])): ?>
                    <div class="kanban-empty">No tasks</div>
                <?php else: ?>
                    <?php foreach ($tasksByStatus['in_progress'] as $task): ?>
                        <?php 
                        $dueClass = '';
                        if ($task['due_date']) {
                            $dueDate = strtotime($task['due_date']);
                            $daysUntil = floor(($dueDate - time()) / 86400);
                            if ($daysUntil < 0) $dueClass = 'overdue';
                            elseif ($daysUntil <= 2) $dueClass = 'soon';
                        }
                        ?>
                        <div class="kanban-task priority-<?= $task['priority'] ?? 'low' ?>" 
                             draggable="true" 
                             data-task-id="<?= $task['id'] ?>">
                            <div class="task-title"><?= Security::sanitizeOutput($task['title']) ?></div>
                            <div class="task-meta">
                                <?php if ($task['due_date']): ?>
                                <span class="task-due <?= $dueClass ?>">
                                    <i class="bi bi-calendar"></i>
                                    <?= date('M j', strtotime($task['due_date'])) ?>
                                </span>
                                <?php else: ?>
                                <span></span>
                                <?php endif; ?>
                                <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($task['priority'] ?? 'low') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="kanban-column column-review" data-status="review">
            <div class="kanban-column-header">
                <span><i class="bi bi-eye me-2"></i>Review</span>
                <span class="count"><?= count($tasksByStatus['review']) ?></span>
            </div>
            <div class="kanban-tasks" data-status="review">
                <?php if (empty($tasksByStatus['review'])): ?>
                    <div class="kanban-empty">No tasks</div>
                <?php else: ?>
                    <?php foreach ($tasksByStatus['review'] as $task): ?>
                        <?php 
                        $dueClass = '';
                        if ($task['due_date']) {
                            $dueDate = strtotime($task['due_date']);
                            $daysUntil = floor(($dueDate - time()) / 86400);
                            if ($daysUntil < 0) $dueClass = 'overdue';
                            elseif ($daysUntil <= 2) $dueClass = 'soon';
                        }
                        ?>
                        <div class="kanban-task priority-<?= $task['priority'] ?? 'low' ?>" 
                             draggable="true" 
                             data-task-id="<?= $task['id'] ?>">
                            <div class="task-title"><?= Security::sanitizeOutput($task['title']) ?></div>
                            <div class="task-meta">
                                <?php if ($task['due_date']): ?>
                                <span class="task-due <?= $dueClass ?>">
                                    <i class="bi bi-calendar"></i>
                                    <?= date('M j', strtotime($task['due_date'])) ?>
                                </span>
                                <?php else: ?>
                                <span></span>
                                <?php endif; ?>
                                <span class="badge bg-<?= $task['priority'] == 'high' ? 'danger' : ($task['priority'] == 'medium' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($task['priority'] ?? 'low') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="kanban-column column-completed" data-status="completed">
            <div class="kanban-column-header">
                <span><i class="bi bi-check-circle me-2"></i>Done</span>
                <span class="count"><?= count($tasksByStatus['completed']) ?></span>
            </div>
            <div class="kanban-tasks" data-status="completed">
                <?php if (empty($tasksByStatus['completed'])): ?>
                    <div class="kanban-empty">No tasks</div>
                <?php else: ?>
                    <?php foreach ($tasksByStatus['completed'] as $task): ?>
                        <div class="kanban-task priority-<?= $task['priority'] ?? 'low' ?>" 
                             draggable="true" 
                             data-task-id="<?= $task['id'] ?>">
                            <div class="task-title" style="text-decoration: line-through; opacity: 0.7;">
                                <?= Security::sanitizeOutput($task['title']) ?>
                            </div>
                            <div class="task-meta">
                                <?php if ($task['completed_at']): ?>
                                <span class="text-success">
                                    <i class="bi bi-check2"></i>
                                    <?= date('M j', strtotime($task['completed_at'])) ?>
                                </span>
                                <?php else: ?>
                                <span></span>
                                <?php endif; ?>
                                <span class="badge bg-success">Done</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tasks = document.querySelectorAll('.kanban-task');
    const columns = document.querySelectorAll('.kanban-tasks');
    let draggedTask = null;

    tasks.forEach(task => {
        task.addEventListener('dragstart', function(e) {
            draggedTask = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', this.dataset.taskId);
        });

        task.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            columns.forEach(col => col.classList.remove('drag-over'));
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
        });

        column.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');
            
            const emptyMsg = this.querySelector('.kanban-empty');
            if (emptyMsg) emptyMsg.remove();
            
            const newStatus = this.dataset.status;
            const taskId = e.dataTransfer.getData('text/plain');
            
            if (draggedTask) {
                this.appendChild(draggedTask);
                
                const formData = new FormData();
                formData.append('status', newStatus);
                
                fetch('/projects/task/status/' + taskId, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateColumnCounts();
                        if (newStatus === 'completed') {
                            showToast('Task completed! +15 XP earned', 'success');
                        }
                    }
                })
                .catch(err => {
                    console.error('Error updating task:', err);
                });
            }
        });
    });

    function updateColumnCounts() {
        document.querySelectorAll('.kanban-column').forEach(col => {
            const tasks = col.querySelectorAll('.kanban-task').length;
            const count = col.querySelector('.count');
            if (count) count.textContent = tasks;
        });
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3`;
        toast.style.zIndex = '9999';
        toast.style.animation = 'slideInUp 0.3s ease-out';
        toast.innerHTML = `<i class="bi bi-check-circle me-2"></i>${message}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutDown 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
