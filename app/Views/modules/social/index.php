<?php 
$pageTitle = 'Social Links'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;

$platformIcons = [
    'LinkedIn' => 'bi-linkedin',
    'Twitter' => 'bi-twitter-x',
    'GitHub' => 'bi-github',
    'Instagram' => 'bi-instagram',
    'Facebook' => 'bi-facebook',
    'YouTube' => 'bi-youtube',
    'TikTok' => 'bi-tiktok',
    'Discord' => 'bi-discord',
    'Twitch' => 'bi-twitch',
    'Medium' => 'bi-medium',
    'Reddit' => 'bi-reddit',
    'Dribbble' => 'bi-dribbble',
    'Behance' => 'bi-behance',
    'Website' => 'bi-globe',
    'Other' => 'bi-link-45deg'
];

$platformColors = [
    'LinkedIn' => '#0077b5',
    'Twitter' => '#000',
    'GitHub' => '#333',
    'Instagram' => '#e4405f',
    'Facebook' => '#1877f2',
    'YouTube' => '#ff0000',
    'TikTok' => '#000',
    'Discord' => '#5865f2',
    'Twitch' => '#9146ff',
    'Medium' => '#000',
    'Reddit' => '#ff4500',
    'Dribbble' => '#ea4c89',
    'Behance' => '#1769ff',
    'Website' => '#667eea',
    'Other' => '#6c757d'
];
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="bi bi-share me-2"></i>Social Links</h2>
            <p class="text-muted mb-0">Manage your social media profiles</p>
        </div>
        <a href="/social-links/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Link
        </a>
    </div>

    <?php if (count($links) > 0): ?>
    <div class="row">
        <?php foreach ($links as $link): 
            $icon = $platformIcons[$link['platform']] ?? 'bi-link-45deg';
            $color = $platformColors[$link['platform']] ?? '#6c757d';
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon me-3" style="background-color: <?= $color ?>; color: white;">
                            <i class="bi <?= $icon ?>"></i>
                        </div>
                        <div>
                            <h5 class="mb-0"><?= Security::sanitizeOutput($link['platform']) ?></h5>
                            <?php if ($link['username']): ?>
                            <small class="text-muted">@<?= Security::sanitizeOutput($link['username']) ?></small>
                            <?php endif; ?>
                        </div>
                        <?php if ($link['is_public']): ?>
                        <span class="badge bg-success ms-auto">Public</span>
                        <?php else: ?>
                        <span class="badge bg-secondary ms-auto">Private</span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?= Security::sanitizeOutput($link['profile_url']) ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100 mb-2">
                        <i class="bi bi-box-arrow-up-right me-1"></i>Visit Profile
                    </a>

                    <?php if ($link['notes']): ?>
                    <p class="small text-muted mb-0 mt-2"><?= Security::sanitizeOutput($link['notes']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group btn-group-sm w-100">
                        <a href="/social-links/edit/<?= $link['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <form method="POST" action="/social-links/delete/<?= $link['id'] ?>" onsubmit="return confirm('Delete this link?')" style="flex: 1;">
                            <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i>Delete
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
        <div class="card-body empty-state">
            <i class="bi bi-share text-muted"></i>
            <h5>No social links added</h5>
            <p class="text-muted">Add your social media profiles to keep them organized</p>
            <a href="/social-links/create" class="btn btn-primary">Add Your First Link</a>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
