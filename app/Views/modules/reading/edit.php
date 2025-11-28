<?php 
$pageTitle = 'Edit Book'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pencil me-2"></i>Edit Book</h5>
                </div>
                <div class="card-body">
                    <form action="/reading/edit/<?= $book['id'] ?>" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="book_title" class="form-label">Book Title *</label>
                                <input type="text" class="form-control" id="book_title" name="book_title" value="<?= Security::sanitizeOutput($book['book_title']) ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="to_read" <?= $book['status'] === 'to_read' ? 'selected' : '' ?>>To Read</option>
                                    <option value="reading" <?= $book['status'] === 'reading' ? 'selected' : '' ?>>Currently Reading</option>
                                    <option value="completed" <?= $book['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" value="<?= Security::sanitizeOutput($book['author']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rating" class="form-label">Rating (1-5)</label>
                                <select class="form-select" id="rating" name="rating">
                                    <option value="">No rating</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= $book['rating'] == $i ? 'selected' : '' ?>><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="started_at" class="form-label">Started Reading</label>
                                <input type="date" class="form-control" id="started_at" name="started_at" value="<?= $book['started_at'] ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="completed_at" class="form-label">Completed</label>
                                <input type="date" class="form-control" id="completed_at" name="completed_at" value="<?= $book['completed_at'] ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"><?= Security::sanitizeOutput($book['notes']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/reading" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
