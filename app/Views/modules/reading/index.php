<?php 
$pageTitle = 'Reading List'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-book me-2"></i>Reading List</h2>
        <a href="/reading/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Book
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4><?= $stats['total'] ?? 0 ?></h4>
                    <small>Total Books</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4><?= $stats['to_read'] ?? 0 ?></h4>
                    <small>To Read</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h4><?= $stats['reading'] ?? 0 ?></h4>
                    <small>Reading</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4><?= $stats['completed'] ?? 0 ?></h4>
                    <small>Completed</small>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($currentlyReading) > 0): ?>
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h6 class="mb-0"><i class="bi bi-book-half me-2"></i>Currently Reading</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($currentlyReading as $book): ?>
                <div class="col-md-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-book display-6 text-warning me-3"></i>
                        <div>
                            <strong><?= Security::sanitizeOutput($book['book_title']) ?></strong>
                            <br><small class="text-muted">by <?= Security::sanitizeOutput($book['author'] ?: 'Unknown') ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (count($books) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Rating</th>
                            <th>Started</th>
                            <th>Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                        <tr>
                            <td><strong><?= Security::sanitizeOutput($book['book_title']) ?></strong></td>
                            <td><?= Security::sanitizeOutput($book['author'] ?: '-') ?></td>
                            <td>
                                <span class="badge bg-<?= $book['status'] === 'completed' ? 'success' : ($book['status'] === 'reading' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst(str_replace('_', ' ', $book['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($book['rating']): ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi bi-star<?= $i <= $book['rating'] ? '-fill text-warning' : '' ?>"></i>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= $book['started_at'] ? date('M d, Y', strtotime($book['started_at'])) : '-' ?></td>
                            <td><?= $book['completed_at'] ? date('M d, Y', strtotime($book['completed_at'])) : '-' ?></td>
                            <td>
                                <a href="/reading/edit/<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/reading/delete/<?= $book['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Remove this book?')">
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
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-book display-1 text-muted"></i>
            <h4 class="mt-3">No books in your list</h4>
            <p class="text-muted">Start building your reading list.</p>
            <a href="/reading/create" class="btn btn-primary">Add Your First Book</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
