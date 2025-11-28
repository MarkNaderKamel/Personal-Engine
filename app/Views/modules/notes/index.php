<?php 
$pageTitle = 'Notes'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-journal-text me-2"></i>Notes</h2>
        <a href="/notes/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Note
        </a>
    </div>

    <?php if (count($notes) > 0): ?>
    <div class="row">
        <?php foreach ($notes as $note): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 <?= $note['is_favorite'] ? 'border-warning' : '' ?>">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <?php if ($note['is_favorite']): ?>
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <?php endif; ?>
                        <?= Security::sanitizeOutput($note['title'] ?: 'Untitled') ?>
                    </h6>
                    <small class="text-muted"><?= date('M d', strtotime($note['created_at'])) ?></small>
                </div>
                <div class="card-body">
                    <p class="card-text"><?= nl2br(Security::sanitizeOutput(substr($note['content'], 0, 200))) ?><?= strlen($note['content']) > 200 ? '...' : '' ?></p>
                    <?php if ($note['tags']): ?>
                    <div class="mb-2">
                        <?php foreach (explode(',', $note['tags']) as $tag): ?>
                        <span class="badge bg-secondary me-1"><?= Security::sanitizeOutput(trim($tag)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group btn-group-sm w-100">
                        <a href="/notes/edit/<?= $note['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="/notes/toggle-favorite/<?= $note['id'] ?>" method="POST" class="d-inline">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-outline-warning">
                                <i class="bi bi-star<?= $note['is_favorite'] ? '-fill' : '' ?>"></i>
                            </button>
                        </form>
                        <form action="/notes/delete/<?= $note['id'] ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete this note?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-journal-text display-1 text-muted"></i>
            <h4 class="mt-3">No notes yet</h4>
            <p class="text-muted">Create your first note to start organizing your thoughts.</p>
            <a href="/notes/create" class="btn btn-primary">Create Note</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
