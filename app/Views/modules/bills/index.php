<?php 
$pageTitle = 'Bills'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bills Management</h2>
        <a href="/bills/create" class="btn btn-primary">Add New Bill</a>
    </div>
    
    <?php if (count($bills) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Bill Name</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Recurring</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $bill): ?>
                <tr>
                    <td><?= Security::sanitizeOutput($bill['bill_name']) ?></td>
                    <td>$<?= number_format($bill['amount'], 2) ?></td>
                    <td><?= date('M d, Y', strtotime($bill['due_date'])) ?></td>
                    <td><?= Security::sanitizeOutput($bill['category'] ?? 'N/A') ?></td>
                    <td>
                        <span class="badge bg-<?= $bill['status'] == 'paid' ? 'success' : 'warning' ?>">
                            <?= $bill['status'] ?>
                        </span>
                    </td>
                    <td><?= $bill['is_recurring'] ? 'Yes (' . $bill['recurring_period'] . ')' : 'No' ?></td>
                    <td>
                        <a href="/bills/edit/<?= $bill['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="/bills/delete/<?= $bill['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No bills found. <a href="/bills/create">Add your first bill</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
