<?php 
$pageTitle = 'Contacts'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Contacts Management</h2>
        <a href="/contacts/create" class="btn btn-primary">Add Contact</a>
    </div>
    
    <?php if (count($upcomingBirthdays) > 0): ?>
    <div class="alert alert-info">
        <h6>Upcoming Birthdays</h6>
        <ul class="mb-0">
            <?php foreach (array_slice($upcomingBirthdays, 0, 5) as $contact): ?>
            <li><?= Security::sanitizeOutput($contact['full_name']) ?> - 
                <?= date('M d', strtotime($contact['birthday'])) ?> 
                (<?= $contact['days_until'] ?> days)
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    
    <?php if (count($contacts) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Relationship</th>
                    <th>Birthday</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= Security::sanitizeOutput($contact['full_name']) ?></td>
                    <td><?= Security::sanitizeOutput($contact['email']) ?></td>
                    <td><?= Security::sanitizeOutput($contact['phone']) ?></td>
                    <td><?= Security::sanitizeOutput($contact['company']) ?></td>
                    <td><?= Security::sanitizeOutput($contact['relationship']) ?></td>
                    <td><?= $contact['birthday'] ? date('M d, Y', strtotime($contact['birthday'])) : 'N/A' ?></td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/contacts/view/<?= $contact['id'] ?>" class="btn btn-sm btn-outline-info" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/contacts/edit/<?= $contact['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="/contacts/delete/<?= $contact['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this contact?')">
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
    <div class="alert alert-info">No contacts found. <a href="/contacts/create">Add your first contact</a></div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
