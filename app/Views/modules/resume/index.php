<?php 
$pageTitle = 'CV Manager'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-file-person me-2"></i>CV Manager</h2>
            <p class="text-muted mb-0">Build and manage your professional profile</p>
        </div>
        <div class="btn-group">
            <a href="/resume/create" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Upload Resume
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <?php if (count($resumes) > 0): ?>
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-files me-2"></i>My Resumes</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($resumes as $resume): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                <strong><?= Security::sanitizeOutput($resume['resume_name']) ?></strong>
                                <?php if ($resume['is_default']): ?>
                                <span class="badge bg-success ms-2">Default</span>
                                <?php endif; ?>
                                <?php if ($resume['target_role']): ?>
                                <br><small class="text-muted">Target: <?= Security::sanitizeOutput($resume['target_role']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <?php if (!$resume['is_default']): ?>
                                <form method="POST" action="/resume/set-default/<?= $resume['id'] ?>" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-outline-success" title="Set as Default">
                                        <i class="bi bi-star"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if ($resume['file_path']): ?>
                                <a href="/<?= Security::sanitizeOutput($resume['file_path']) ?>" class="btn btn-outline-primary" target="_blank" title="Download">
                                    <i class="bi bi-download"></i>
                                </a>
                                <?php endif; ?>
                                <form method="POST" action="/resume/delete-resume/<?= $resume['id'] ?>" style="display:inline;" onsubmit="return confirm('Delete this resume?')">
                                    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-briefcase me-2"></i>Work Experience</h6>
                    <a href="/resume/add-experience" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($workExperience) > 0): ?>
                    <div class="activity-timeline">
                        <?php foreach ($workExperience as $exp): ?>
                        <div class="activity-item mb-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?= Security::sanitizeOutput($exp['job_title']) ?></h6>
                                    <p class="mb-1 text-primary"><?= Security::sanitizeOutput($exp['company_name']) ?></p>
                                    <small class="text-muted">
                                        <?= $exp['start_date'] ? date('M Y', strtotime($exp['start_date'])) : '' ?> - 
                                        <?= $exp['is_current'] ? 'Present' : ($exp['end_date'] ? date('M Y', strtotime($exp['end_date'])) : '') ?>
                                        <?php if ($exp['location']): ?> | <?= Security::sanitizeOutput($exp['location']) ?><?php endif; ?>
                                    </small>
                                    <?php if ($exp['description']): ?>
                                    <p class="mt-2 mb-0 small"><?= nl2br(Security::sanitizeOutput($exp['description'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="/resume/edit-experience/<?= $exp['id'] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/resume/delete-experience/<?= $exp['id'] ?>" style="display:inline;" onsubmit="return confirm('Delete?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No work experience added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Education</h6>
                    <a href="/resume/add-education" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($education) > 0): ?>
                    <?php foreach ($education as $edu): ?>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="mb-1"><?= Security::sanitizeOutput($edu['degree']) ?> in <?= Security::sanitizeOutput($edu['field_of_study']) ?></h6>
                            <p class="mb-1 text-primary"><?= Security::sanitizeOutput($edu['institution_name']) ?></p>
                            <small class="text-muted">
                                <?= $edu['start_date'] ? date('Y', strtotime($edu['start_date'])) : '' ?> - 
                                <?= $edu['is_current'] ? 'Present' : ($edu['end_date'] ? date('Y', strtotime($edu['end_date'])) : '') ?>
                                <?php if ($edu['gpa']): ?> | GPA: <?= Security::sanitizeOutput($edu['gpa']) ?><?php endif; ?>
                            </small>
                        </div>
                        <form method="POST" action="/resume/delete-education/<?= $edu['id'] ?>" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No education added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-award me-2"></i>Certifications</h6>
                    <a href="/resume/add-certification" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($certifications) > 0): ?>
                    <div class="row">
                        <?php foreach ($certifications as $cert): ?>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1"><?= Security::sanitizeOutput($cert['certification_name']) ?></h6>
                                        <small class="text-muted"><?= Security::sanitizeOutput($cert['issuing_organization']) ?></small>
                                        <br><small class="text-muted">Issued: <?= $cert['issue_date'] ? date('M Y', strtotime($cert['issue_date'])) : 'N/A' ?></small>
                                    </div>
                                    <form method="POST" action="/resume/delete-certification/<?= $cert['id'] ?>" onsubmit="return confirm('Delete?')">
                                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No certifications added yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Skills</h6>
                    <a href="/resume/add-skill" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (count($skills) > 0): ?>
                    <?php 
                    $groupedSkills = [];
                    foreach ($skills as $skill) {
                        $cat = $skill['category'] ?: 'Other';
                        if (!isset($groupedSkills[$cat])) $groupedSkills[$cat] = [];
                        $groupedSkills[$cat][] = $skill;
                    }
                    ?>
                    <?php foreach ($groupedSkills as $category => $categorySkills): ?>
                    <h6 class="small text-uppercase text-muted mb-2"><?= Security::sanitizeOutput($category) ?></h6>
                    <div class="mb-3">
                        <?php foreach ($categorySkills as $skill): ?>
                        <span class="badge bg-primary-soft me-1 mb-1 d-inline-flex align-items-center">
                            <?= Security::sanitizeOutput($skill['skill_name']) ?>
                            <form method="POST" action="/resume/delete-skill/<?= $skill['id'] ?>" class="d-inline ms-1" onsubmit="return confirm('Remove skill?')">
                                <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                                <button type="submit" class="btn btn-link btn-sm p-0 text-danger" style="font-size: 0.75rem;">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p class="text-muted text-center mb-0">No skills added yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Quick Tips</h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li class="mb-2">Keep your resume updated with latest achievements</li>
                        <li class="mb-2">Use action verbs to describe accomplishments</li>
                        <li class="mb-2">Tailor your resume for each job application</li>
                        <li>Include quantifiable results when possible</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
