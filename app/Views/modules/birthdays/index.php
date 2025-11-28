<?php 
$pageTitle = 'Birthdays'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-balloon me-2"></i>Birthdays</h2>
            <p class="text-muted mb-0">Never forget an important birthday</p>
        </div>
        <a href="/birthdays/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Birthday
        </a>
    </div>

    <?php if (count($today) > 0): ?>
    <div class="alert alert-success mb-4">
        <h5 class="alert-heading"><i class="bi bi-cake2 me-2"></i>Today's Birthdays!</h5>
        <?php foreach ($today as $bday): ?>
        <p class="mb-0"><?= Security::sanitizeOutput($bday['person_name']) ?> is celebrating today!</p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-heart me-2"></i>Upcoming Birthdays</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (count($upcoming) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($upcoming as $bday): 
                            $nextBirthday = $bday['next_birthday'] ?? null;
                            $daysUntil = $nextBirthday ? floor((strtotime($nextBirthday) - time()) / 86400) : null;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= Security::sanitizeOutput($bday['person_name']) ?></strong>
                                <?php if ($bday['relationship']): ?>
                                <br><small class="text-muted"><?= Security::sanitizeOutput($bday['relationship']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-<?= $daysUntil !== null && $daysUntil <= 7 ? 'warning' : 'primary' ?>">
                                    <?= date('M d', strtotime($bday['birth_date'])) ?>
                                </span>
                                <?php if ($daysUntil !== null): ?>
                                <br><small class="text-muted"><?= $daysUntil == 0 ? 'Today!' : ($daysUntil == 1 ? 'Tomorrow' : "In {$daysUntil} days") ?></small>
                                <?php endif; ?>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        No upcoming birthdays
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-month me-2"></i>This Month</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (count($thisMonth) > 0): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($thisMonth as $bday): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><?= Security::sanitizeOutput($bday['person_name']) ?></span>
                            <span class="badge bg-secondary"><?= date('d', strtotime($bday['birth_date'])) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        No birthdays this month
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Stats</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><i class="bi bi-people me-2"></i><strong><?= count($birthdays) ?></strong> birthdays tracked</p>
                    <p class="mb-2"><i class="bi bi-calendar-check me-2"></i><strong><?= count($upcoming) ?></strong> upcoming</p>
                    <p class="mb-0"><i class="bi bi-cake2 me-2"></i><strong><?= count($today) ?></strong> today</p>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($birthdays) > 0): ?>
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0"><i class="bi bi-list me-2"></i>All Birthdays</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birthday</th>
                            <th>Relationship</th>
                            <th>Reminder</th>
                            <th>Gift Ideas</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($birthdays as $bday): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($bday['person_name']) ?></strong></td>
                            <td><?= date('F d', strtotime($bday['birth_date'])) ?></td>
                            <td><?= Security::sanitizeOutput($bday['relationship'] ?: '-') ?></td>
                            <td><?= $bday['reminder_days'] ?> days before</td>
                            <td>
                                <?php if ($bday['gift_ideas']): ?>
                                <span class="text-truncate d-inline-block" style="max-width: 150px;" title="<?= Security::sanitizeOutput($bday['gift_ideas']) ?>">
                                    <?= Security::sanitizeOutput(substr($bday['gift_ideas'], 0, 30)) ?>...
                                </span>
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/birthdays/edit/<?= $bday['id'] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/birthdays/delete/<?= $bday['id'] ?>" style="display:inline;" onsubmit="return confirm('Delete?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger">
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
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body empty-state">
            <i class="bi bi-balloon text-muted"></i>
            <h5>No birthdays tracked yet</h5>
            <p class="text-muted">Add birthdays of friends and family to never forget!</p>
            <a href="/birthdays/create" class="btn btn-primary">Add First Birthday</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
