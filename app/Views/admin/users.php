<?php 
$pageTitle = 'Manage Users'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2>User Management</h2>
    
    <div class="table-responsive mt-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= Security::sanitizeOutput($user['email']) ?></td>
                    <td><?= Security::sanitizeOutput($user['first_name'] . ' ' . $user['last_name']) ?></td>
                    <td><span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : 'primary' ?>"><?= $user['role'] ?></span></td>
                    <td><?= $user['email_verified'] ? 'Yes' : 'No' ?></td>
                    <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <form method="POST" action="/admin/users/delete/<?= $user['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
