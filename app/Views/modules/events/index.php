<?php 
$pageTitle = 'Events & Calendar'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$prevMonth = $month == 1 ? 12 : $month - 1;
$prevYear = $month == 1 ? $year - 1 : $year;
$nextMonth = $month == 12 ? 1 : $month + 1;
$nextYear = $month == 12 ? $year + 1 : $year;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-event me-2"></i>Events & Calendar</h2>
        <a href="/events/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Event
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="/events?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    <h5 class="mb-0"><?= $monthNames[$month] ?> <?= $year ?></h5>
                    <a href="/events?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($events) > 0): ?>
                    <div class="list-group">
                        <?php foreach ($events as $event): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= Security::sanitizeOutput($event['event_title']) ?></h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('F d, Y', strtotime($event['event_date'])) ?>
                                        <?php if ($event['event_time']): ?>
                                        <i class="bi bi-clock ms-2 me-1"></i><?= date('g:i A', strtotime($event['event_time'])) ?>
                                        <?php endif; ?>
                                    </p>
                                    <?php if ($event['location']): ?>
                                    <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-1"></i><?= Security::sanitizeOutput($event['location']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="/events/edit/<?= $event['id'] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="/events/delete/<?= $event['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center py-4">No events this month</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-sun me-2"></i>Today's Events</h6>
                </div>
                <div class="card-body">
                    <?php if (count($todayEvents) > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($todayEvents as $event): ?>
                        <li class="mb-2 pb-2 border-bottom">
                            <strong><?= Security::sanitizeOutput($event['event_title']) ?></strong>
                            <?php if ($event['event_time']): ?>
                            <br><small class="text-muted"><?= date('g:i A', strtotime($event['event_time'])) ?></small>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted mb-0">No events today</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Upcoming (Next 7 Days)</h6>
                </div>
                <div class="card-body">
                    <?php if (count($upcomingEvents) > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($upcomingEvents as $event): ?>
                        <li class="mb-2 pb-2 border-bottom">
                            <strong><?= Security::sanitizeOutput($event['event_title']) ?></strong>
                            <br><small class="text-muted"><?= date('M d', strtotime($event['event_date'])) ?>
                            <?php if ($event['event_time']): ?> at <?= date('g:i A', strtotime($event['event_time'])) ?><?php endif; ?>
                            </small>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted mb-0">No upcoming events</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
