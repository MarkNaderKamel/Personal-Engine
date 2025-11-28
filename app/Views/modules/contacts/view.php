<?php $pageTitle = 'View Contact'; include __DIR__ . '/../../layouts/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/contacts">Contacts</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($contact['full_name']) ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Contact Details</h5>
                    <div>
                        <a href="/contacts/edit/<?= $contact['id'] ?>" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="relationship-avatar me-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            <?= strtoupper(substr($contact['full_name'], 0, 2)) ?>
                        </div>
                        <div>
                            <h3 class="mb-1"><?= htmlspecialchars($contact['full_name']) ?></h3>
                            <?php if ($contact['relationship']): ?>
                            <span class="badge bg-primary"><?= htmlspecialchars($contact['relationship']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2"><i class="bi bi-envelope me-2"></i>Email</h6>
                            <p class="mb-0">
                                <?php if ($contact['email']): ?>
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>"><?= htmlspecialchars($contact['email']) ?></a>
                                <?php else: ?>
                                <span class="text-muted">Not provided</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2"><i class="bi bi-telephone me-2"></i>Phone</h6>
                            <p class="mb-0">
                                <?php if ($contact['phone']): ?>
                                <a href="tel:<?= htmlspecialchars($contact['phone']) ?>"><?= htmlspecialchars($contact['phone']) ?></a>
                                <?php else: ?>
                                <span class="text-muted">Not provided</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2"><i class="bi bi-building me-2"></i>Company</h6>
                            <p class="mb-0">
                                <?= $contact['company'] ? htmlspecialchars($contact['company']) : '<span class="text-muted">Not provided</span>' ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-2"><i class="bi bi-cake me-2"></i>Birthday</h6>
                            <p class="mb-0">
                                <?= $contact['birthday'] ? date('F j, Y', strtotime($contact['birthday'])) : '<span class="text-muted">Not provided</span>' ?>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($contact['address']): ?>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-geo-alt me-2"></i>Address</h6>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($contact['address'])) ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($contact['notes']): ?>
                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-sticky me-2"></i>Notes</h6>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($contact['notes'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        Added on <?= date('F j, Y', strtotime($contact['created_at'])) ?>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <?php if ($contact['email']): ?>
                    <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-envelope me-2"></i>Send Email
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($contact['phone']): ?>
                    <a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="btn btn-outline-success w-100 mb-2">
                        <i class="bi bi-telephone me-2"></i>Call
                    </a>
                    <?php endif; ?>
                    
                    <a href="/contacts/edit/<?= $contact['id'] ?>" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit Contact
                    </a>
                    
                    <form method="POST" action="/contacts/delete/<?= $contact['id'] ?>" 
                          onsubmit="return confirm('Are you sure you want to delete this contact?')">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash me-2"></i>Delete Contact
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
