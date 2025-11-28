<?php 
$pageTitle = 'New Note'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-plus-lg me-2"></i>Create New Note</h5>
                </div>
                <div class="card-body">
                    <form action="/notes/create" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Note title...">
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="10" placeholder="Write your note here..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="tags" class="form-label">Tags (comma-separated)</label>
                                <input type="text" class="form-control" id="tags" name="tags" placeholder="work, personal, ideas">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_favorite" name="is_favorite">
                                    <label class="form-check-label" for="is_favorite">
                                        <i class="bi bi-star text-warning"></i> Mark as favorite
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/notes" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Note</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
