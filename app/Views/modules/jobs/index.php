<?php 
$pageTitle = 'Job Applications'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$statusColors = [
    'applied' => 'primary',
    'reviewing' => 'info',
    'interviewing' => 'warning',
    'offered' => 'success',
    'accepted' => 'success',
    'rejected' => 'danger',
    'withdrawn' => 'secondary'
];

$priorityColors = [
    'high' => 'danger',
    'medium' => 'warning',
    'low' => 'secondary'
];
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-briefcase me-2"></i>Job Applications</h2>
            <p class="text-muted mb-0">Track your job search journey</p>
        </div>
        <a href="/jobs/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Application
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-collection display-6 mb-2"></i>
                    <h4><?= $stats['total'] ?></h4>
                    <small>Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-send display-6 mb-2"></i>
                    <h4><?= $stats['applied'] ?></h4>
                    <small>Applied</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-chat-dots display-6 mb-2"></i>
                    <h4><?= $stats['interviewing'] ?></h4>
                    <small>Interviewing</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-trophy display-6 mb-2"></i>
                    <h4><?= $stats['offered'] ?></h4>
                    <small>Offered</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card bg-danger text-white h-100">
                <div class="card-body stat-card">
                    <i class="bi bi-x-circle display-6 mb-2"></i>
                    <h4><?= $stats['rejected'] ?></h4>
                    <small>Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 mb-3">
            <div class="card text-white h-100" style="background: var(--success-gradient);">
                <div class="card-body stat-card">
                    <i class="bi bi-check-circle display-6 mb-2"></i>
                    <h4><?= $stats['accepted'] ?></h4>
                    <small>Accepted</small>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($applications) > 0): ?>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Position</th>
                            <th>Location</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $app): ?>
                        <tr>
                            <td>
                                <strong><?= Security::sanitizeOutput($app['company_name']) ?></strong>
                                <?php if ($app['job_url']): ?>
                                <a href="<?= Security::sanitizeOutput($app['job_url']) ?>" target="_blank" class="text-muted ms-1">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= Security::sanitizeOutput($app['job_title']) ?>
                                <?php if ($app['salary_range']): ?>
                                <br><small class="text-success"><?= Security::sanitizeOutput($app['salary_range']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($app['location']): ?>
                                <i class="bi bi-geo-alt text-muted me-1"></i><?= Security::sanitizeOutput($app['location']) ?>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $app['application_date'] ? date('M d, Y', strtotime($app['application_date'])) : '-' ?></td>
                            <td>
                                <span class="badge bg-<?= $statusColors[$app['status']] ?? 'secondary' ?>">
                                    <?= ucfirst($app['status']) ?>
                                </span>
                                <?php if ($app['interview_date'] && strtotime($app['interview_date']) > time()): ?>
                                <br><small class="text-warning"><i class="bi bi-calendar-event me-1"></i>Interview: <?= date('M d', strtotime($app['interview_date'])) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $priorityColors[$app['priority']] ?? 'secondary' ?>">
                                    <?= ucfirst($app['priority']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/jobs/view/<?= $app['id'] ?>" class="btn btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/jobs/edit/<?= $app['id'] ?>" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#statusModal<?= $app['id'] ?>" title="Update Status">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                    <form method="POST" action="/jobs/delete/<?= $app['id'] ?>" style="display:inline;" onsubmit="return confirm('Delete this application?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="modal fade" id="statusModal<?= $app['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <form method="POST" action="/jobs/status/<?= $app['id'] ?>">
                                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Update Status</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <select name="status" class="form-select">
                                                        <option value="applied" <?= $app['status'] === 'applied' ? 'selected' : '' ?>>Applied</option>
                                                        <option value="reviewing" <?= $app['status'] === 'reviewing' ? 'selected' : '' ?>>Reviewing</option>
                                                        <option value="interviewing" <?= $app['status'] === 'interviewing' ? 'selected' : '' ?>>Interviewing</option>
                                                        <option value="offered" <?= $app['status'] === 'offered' ? 'selected' : '' ?>>Offered</option>
                                                        <option value="accepted" <?= $app['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                                        <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                        <option value="withdrawn" <?= $app['status'] === 'withdrawn' ? 'selected' : '' ?>>Withdrawn</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
        <div class="card-body empty-state">
            <i class="bi bi-briefcase text-muted"></i>
            <h5>No job applications yet</h5>
            <p class="text-muted">Start tracking your job search by adding your first application.</p>
            <a href="/jobs/create" class="btn btn-primary">Add Your First Application</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
