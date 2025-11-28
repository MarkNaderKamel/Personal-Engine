<?php 
$pageTitle = 'Add Book'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Add Book to Reading List</h5>
                </div>
                <div class="card-body">
                    <form action="/reading/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="book_title" class="form-label">Book Title *</label>
                                <input type="text" class="form-control" id="book_title" name="book_title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="to_read">To Read</option>
                                    <option value="reading">Currently Reading</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="rating" class="form-label">Rating (1-5)</label>
                                <select class="form-select" id="rating" name="rating">
                                    <option value="">No rating</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="started_at" class="form-label">Started Reading</label>
                                <input type="date" class="form-control" id="started_at" name="started_at">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="completed_at" class="form-label">Completed</label>
                                <input type="date" class="form-control" id="completed_at" name="completed_at">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Your thoughts, favorite quotes, notes..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/reading" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
