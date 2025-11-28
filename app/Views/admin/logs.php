<?php 
$pageTitle = 'Activity Logs'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2>Activity Logs</h2>
    
    <div class="table-responsive mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Action</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['user_id'] ?></td>
                    <td><?= Security::sanitizeOutput($log['action']) ?></td>
                    <td><?= Security::sanitizeOutput($log['ip_address']) ?></td>
                    <td><?= date('M d, Y H:i:s', strtotime($log['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
