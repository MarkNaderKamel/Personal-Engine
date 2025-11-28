<?php 
$pageTitle = 'Job Application Details'; 
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
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/jobs">Job Applications</a></li>
                    <li class="breadcrumb-item active"><?= Security::sanitizeOutput($application['company_name']) ?></li>
                </ol>
            </nav>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1"><?= Security::sanitizeOutput($application['job_title']) ?></h4>
                        <h6 class="text-muted mb-0"><?= Security::sanitizeOutput($application['company_name']) ?></h6>
                    </div>
                    <span class="badge bg-<?= $statusColors[$application['status']] ?? 'secondary' ?> fs-6">
                        <?= ucfirst($application['status']) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2"><i class="bi bi-geo-alt text-muted me-2"></i><strong>Location:</strong> <?= Security::sanitizeOutput($application['location'] ?: 'Not specified') ?></p>
                            <p class="mb-2"><i class="bi bi-briefcase text-muted me-2"></i><strong>Type:</strong> <?= ucfirst($application['job_type'] ?? 'Full-time') ?></p>
                            <p class="mb-2"><i class="bi bi-currency-dollar text-muted me-2"></i><strong>Salary:</strong> <?= Security::sanitizeOutput($application['salary_range'] ?: 'Not specified') ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><i class="bi bi-calendar text-muted me-2"></i><strong>Applied:</strong> <?= $application['application_date'] ? date('F d, Y', strtotime($application['application_date'])) : 'Not specified' ?></p>
                            <p class="mb-2"><i class="bi bi-link text-muted me-2"></i><strong>Source:</strong> <?= Security::sanitizeOutput($application['source'] ?: 'Not specified') ?></p>
                            <?php if ($application['job_url']): ?>
                            <p class="mb-2"><i class="bi bi-box-arrow-up-right text-muted me-2"></i><a href="<?= Security::sanitizeOutput($application['job_url']) ?>" target="_blank">View Job Posting</a></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($application['job_description']): ?>
                    <h6 class="border-bottom pb-2 mb-3">Job Description</h6>
                    <p class="mb-4"><?= nl2br(Security::sanitizeOutput($application['job_description'])) ?></p>
                    <?php endif; ?>

                    <?php if ($application['interview_date']): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-calendar-event me-2"></i>
                        <strong>Interview Scheduled:</strong> <?= date('F d, Y \a\t g:i A', strtotime($application['interview_date'])) ?>
                        <?php if ($application['interview_type']): ?>
                        (<?= Security::sanitizeOutput($application['interview_type']) ?>)
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($application['notes']): ?>
                    <h6 class="border-bottom pb-2 mb-3">Notes</h6>
                    <p><?= nl2br(Security::sanitizeOutput($application['notes'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <a href="/jobs/edit/<?= $application['id'] ?>" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="/jobs" class="btn btn-outline-secondary">Back to List</a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <?php if ($application['contact_name'] || $application['contact_email'] || $application['contact_phone']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-person me-2"></i>Contact Information</h6>
                </div>
                <div class="card-body">
                    <?php if ($application['contact_name']): ?>
                    <p class="mb-2"><strong><?= Security::sanitizeOutput($application['contact_name']) ?></strong></p>
                    <?php endif; ?>
                    <?php if ($application['contact_email']): ?>
                    <p class="mb-2"><i class="bi bi-envelope me-2"></i><a href="mailto:<?= Security::sanitizeOutput($application['contact_email']) ?>"><?= Security::sanitizeOutput($application['contact_email']) ?></a></p>
                    <?php endif; ?>
                    <?php if ($application['contact_phone']): ?>
                    <p class="mb-0"><i class="bi bi-telephone me-2"></i><?= Security::sanitizeOutput($application['contact_phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <strong>Application Created</strong>
                            <br><small class="text-muted"><?= date('M d, Y', strtotime($application['created_at'])) ?></small>
                        </div>
                        <?php if ($application['updated_at'] !== $application['created_at']): ?>
                        <div class="activity-item">
                            <strong>Last Updated</strong>
                            <br><small class="text-muted"><?= date('M d, Y', strtotime($application['updated_at'])) ?></small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
