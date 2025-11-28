<?php 
$pageTitle = 'Documents'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Document Management</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            Upload Document
        </button>
    </div>
    
    <?php if (count($documents) > 0): ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Category</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc): ?>
                <tr>
                    <td>
                        <i class="bi bi-file-earmark"></i>
                        <?= Security::sanitizeOutput($doc['original_name']) ?>
                    </td>
                    <td><?= Security::sanitizeOutput($doc['category']) ?></td>
                    <td><?= number_format($doc['file_size'] / 1024, 2) ?> KB</td>
                    <td><?= date('M d, Y', strtotime($doc['created_at'])) ?></td>
                    <td>
                        <a href="/<?= $doc['file_path'] ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                        <form method="POST" action="/documents/delete/<?= $doc['id'] ?>" style="display:inline;" onsubmit="return confirm('Are you sure?')">
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
    <div class="alert alert-info">No documents found. Upload your first document!</div>
    <?php endif; ?>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/documents/upload" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document" class="form-label">Select File *</label>
                        <input type="file" class="form-control" id="document" name="document" required>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="general">General</option>
                            <option value="financial">Financial</option>
                            <option value="legal">Legal</option>
                            <option value="medical">Medical</option>
                            <option value="personal">Personal</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
