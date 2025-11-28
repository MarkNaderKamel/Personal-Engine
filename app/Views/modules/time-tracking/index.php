<?php 
$pageTitle = 'Time Tracking'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

function formatDuration($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
}
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-stopwatch me-2"></i>Time Tracking</h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4><?= formatDuration($todayTotal) ?></h4>
                    <small>Today</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4><?= formatDuration($weekTotal) ?></h4>
                    <small>This Week</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card <?= $activeTimer ? 'bg-success' : 'bg-secondary' ?> text-white">
                <div class="card-body text-center">
                    <?php if ($activeTimer): ?>
                    <h4 id="active-timer">00:00:00</h4>
                    <small>Currently Tracking</small>
                    <?php else: ?>
                    <h4>--:--:--</h4>
                    <small>No Active Timer</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><?= $activeTimer ? 'Stop Timer' : 'Start Timer' ?></h6>
                </div>
                <div class="card-body">
                    <?php if ($activeTimer): ?>
                    <div class="text-center mb-3">
                        <p class="mb-1"><strong>Tracking:</strong> <?= Security::sanitizeOutput($activeTimer['task_title'] ?: 'General') ?></p>
                        <p class="text-muted small">Started: <?= date('g:i A', strtotime($activeTimer['start_time'])) ?></p>
                    </div>
                    <form action="/time-tracking/stop/<?= $activeTimer['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-stop-circle me-2"></i>Stop Timer
                        </button>
                    </form>
                    <?php else: ?>
                    <form action="/time-tracking/start" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        <div class="mb-3">
                            <label for="task_id" class="form-label">Task (Optional)</label>
                            <select class="form-select" id="task_id" name="task_id">
                                <option value="">No specific task</option>
                                <?php foreach ($tasks as $task): ?>
                                    <?php if ($task['status'] !== 'completed'): ?>
                                    <option value="<?= $task['id'] ?>"><?= Security::sanitizeOutput($task['title']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <input type="text" class="form-control" id="notes" name="notes" placeholder="What are you working on?">
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play-circle me-2"></i>Start Timer
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Recent Time Entries</h6>
                </div>
                <div class="card-body">
                    <?php if (count($entries) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Date</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entries as $entry): ?>
                                <tr>
                                    <td><?= Security::sanitizeOutput($entry['task_title'] ?: 'General') ?></td>
                                    <td><?= date('M d', strtotime($entry['start_time'])) ?></td>
                                    <td><?= date('g:i A', strtotime($entry['start_time'])) ?></td>
                                    <td><?= $entry['end_time'] ? date('g:i A', strtotime($entry['end_time'])) : '-' ?></td>
                                    <td><?= $entry['duration'] ? formatDuration($entry['duration']) : '-' ?></td>
                                    <td>
                                        <form action="/time-tracking/delete/<?= $entry['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this entry?')">
                                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-4">No time entries yet. Start tracking to see your entries here.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($activeTimer): ?>
<script>
const startTime = new Date('<?= $activeTimer['start_time'] ?>').getTime();

function updateTimer() {
    const now = new Date().getTime();
    const elapsed = Math.floor((now - startTime) / 1000);
    
    const hours = Math.floor(elapsed / 3600);
    const minutes = Math.floor((elapsed % 3600) / 60);
    const seconds = elapsed % 60;
    
    document.getElementById('active-timer').textContent = 
        String(hours).padStart(2, '0') + ':' + 
        String(minutes).padStart(2, '0') + ':' + 
        String(seconds).padStart(2, '0');
}

updateTimer();
setInterval(updateTimer, 1000);
</script>
<?php endif; ?>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
