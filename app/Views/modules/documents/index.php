<?php 
$pageTitle = 'Documents'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$fileIcons = [
    'pdf' => 'bi-file-earmark-pdf text-danger',
    'doc' => 'bi-file-earmark-word text-primary',
    'docx' => 'bi-file-earmark-word text-primary',
    'txt' => 'bi-file-earmark-text text-secondary',
    'jpg' => 'bi-file-earmark-image text-success',
    'jpeg' => 'bi-file-earmark-image text-success',
    'png' => 'bi-file-earmark-image text-success',
    'gif' => 'bi-file-earmark-image text-success',
    'xlsx' => 'bi-file-earmark-spreadsheet text-success',
    'csv' => 'bi-file-earmark-spreadsheet text-success'
];
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-folder me-2"></i>Document Management</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-1"></i>Upload Document
        </button>
    </div>
    
    <?php if (count($documents) > 0): ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Description</th>
                            <th>Uploaded</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $doc): 
                            $ext = strtolower($doc['file_type'] ?? 'txt');
                            $iconClass = $fileIcons[$ext] ?? 'bi-file-earmark text-secondary';
                        ?>
                        <tr>
                            <td>
                                <i class="bi <?= $iconClass ?> me-2"></i>
                                <strong><?= Security::sanitizeOutput($doc['original_name']) ?></strong>
                            </td>
                            <td><span class="badge bg-secondary"><?= Security::sanitizeOutput($doc['category']) ?></span></td>
                            <td><?= number_format($doc['file_size'] / 1024, 2) ?> KB</td>
                            <td>
                                <?php if ($doc['description']): ?>
                                    <?= Security::sanitizeOutput(substr($doc['description'], 0, 50)) ?><?= strlen($doc['description']) > 50 ? '...' : '' ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($doc['created_at'])) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/documents/download/<?= $doc['id'] ?>" class="btn btn-outline-primary" title="Download">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="/<?= $doc['file_path'] ?>" class="btn btn-outline-info" target="_blank" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="/documents/delete/<?= $doc['id'] ?>" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
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
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-folder"></i>
                <h5>No Documents</h5>
                <p class="text-muted">Upload your first document to get started</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Document
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cloud-upload me-2"></i>Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/documents/upload" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document" class="form-label">Select File *</label>
                        <input type="file" class="form-control" id="document" name="document" required>
                        <small class="text-muted">Supported: PDF, DOC, DOCX, TXT, JPG, PNG, GIF (Max 10MB)</small>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category">
                            <option value="general">General</option>
                            <option value="financial">Financial</option>
                            <option value="legal">Legal</option>
                            <option value="medical">Medical</option>
                            <option value="personal">Personal</option>
                            <option value="work">Work</option>
                            <option value="education">Education</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Add a brief description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
