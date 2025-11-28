<?php 
$pageTitle = 'Notifications'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Notifications</h2>
        <?php if (count($notifications) > 0): ?>
        <form method="POST" action="/notifications/read-all" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
            <button type="submit" class="btn btn-primary">Mark All as Read</button>
        </form>
        <?php endif; ?>
    </div>
    
    <?php if (count($notifications) > 0): ?>
    <div class="list-group">
        <?php foreach ($notifications as $notification): ?>
        <div class="list-group-item <?= !$notification['is_read'] ? 'list-group-item-primary' : '' ?>">
            <div class="d-flex justify-content-between">
                <h6 class="mb-1"><?= Security::sanitizeOutput($notification['title']) ?></h6>
                <small><?= date('M d, Y H:i', strtotime($notification['created_at'])) ?></small>
            </div>
            <p class="mb-1"><?= Security::sanitizeOutput($notification['message']) ?></p>
            <?php if (!$notification['is_read']): ?>
            <form method="POST" action="/notifications/read/<?= $notification['id'] ?>" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <button type="submit" class="btn btn-sm btn-primary mt-2">Mark as Read</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No notifications</div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
