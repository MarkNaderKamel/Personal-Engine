<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-clock-history me-2"></i>Wellness History</h1>
            <p class="text-muted mb-0">View all your wellness logs</p>
        </div>
        <a href="/wellness" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($logs)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <h4 class="mt-3">No Wellness Logs Yet</h4>
                    <p class="text-muted">Start tracking your wellness journey today!</p>
                    <a href="/wellness/log" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Log Today
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Water</th>
                                <th class="text-center">Sleep</th>
                                <th class="text-center">Mood</th>
                                <th class="text-center">Energy</th>
                                <th class="text-center">Stress</th>
                                <th class="text-center">Exercise</th>
                                <th class="text-center">Steps</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td>
                                        <strong><?= date('M j, Y', strtotime($log['recorded_at'])) ?></strong>
                                        <small class="d-block text-muted"><?= date('l', strtotime($log['recorded_at'])) ?></small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $log['water_intake'] ?>L</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info"><?= $log['sleep_hours'] ?>h</span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($log['mood_score']): ?>
                                            <span class="badge bg-success"><?= $log['mood_score'] ?>/10</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($log['energy_level']): ?>
                                            <span class="badge bg-warning"><?= $log['energy_level'] ?>/10</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($log['stress_level']): ?>
                                            <span class="badge bg-danger"><?= $log['stress_level'] ?>/10</span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary"><?= $log['exercise_minutes'] ?>m</span>
                                    </td>
                                    <td class="text-center">
                                        <?= number_format($log['steps_count']) ?>
                                    </td>
                                    <td>
                                        <form method="POST" action="/wellness/delete/<?= $log['id'] ?>" 
                                              onsubmit="return confirm('Delete this log?')">
                                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
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
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
