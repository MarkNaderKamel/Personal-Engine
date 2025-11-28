<?php 
$pageTitle = 'Edit Social Link'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Social Link</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/social-links/edit/<?= $link['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Platform *</label>
                            <select name="platform" class="form-select" required>
                                <option value="LinkedIn" <?= $link['platform'] === 'LinkedIn' ? 'selected' : '' ?>>LinkedIn</option>
                                <option value="Twitter" <?= $link['platform'] === 'Twitter' ? 'selected' : '' ?>>Twitter / X</option>
                                <option value="GitHub" <?= $link['platform'] === 'GitHub' ? 'selected' : '' ?>>GitHub</option>
                                <option value="Instagram" <?= $link['platform'] === 'Instagram' ? 'selected' : '' ?>>Instagram</option>
                                <option value="Facebook" <?= $link['platform'] === 'Facebook' ? 'selected' : '' ?>>Facebook</option>
                                <option value="YouTube" <?= $link['platform'] === 'YouTube' ? 'selected' : '' ?>>YouTube</option>
                                <option value="TikTok" <?= $link['platform'] === 'TikTok' ? 'selected' : '' ?>>TikTok</option>
                                <option value="Discord" <?= $link['platform'] === 'Discord' ? 'selected' : '' ?>>Discord</option>
                                <option value="Twitch" <?= $link['platform'] === 'Twitch' ? 'selected' : '' ?>>Twitch</option>
                                <option value="Medium" <?= $link['platform'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="Reddit" <?= $link['platform'] === 'Reddit' ? 'selected' : '' ?>>Reddit</option>
                                <option value="Dribbble" <?= $link['platform'] === 'Dribbble' ? 'selected' : '' ?>>Dribbble</option>
                                <option value="Behance" <?= $link['platform'] === 'Behance' ? 'selected' : '' ?>>Behance</option>
                                <option value="Website" <?= $link['platform'] === 'Website' ? 'selected' : '' ?>>Personal Website</option>
                                <option value="Other" <?= $link['platform'] === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile URL *</label>
                            <input type="url" name="profile_url" class="form-control" value="<?= Security::sanitizeOutput($link['profile_url']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" name="username" class="form-control" value="<?= Security::sanitizeOutput($link['username'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_public" class="form-check-input" id="is_public" <?= $link['is_public'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_public">Make this link public on my profile</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"><?= Security::sanitizeOutput($link['notes'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Link
                            </button>
                            <a href="/social-links" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
