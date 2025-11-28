<?php 
$pageTitle = 'Add Social Link'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-share me-2"></i>Add Social Link</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/social-links/create">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Platform *</label>
                            <select name="platform" class="form-select" required>
                                <option value="">Select Platform...</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="Twitter">Twitter / X</option>
                                <option value="GitHub">GitHub</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Facebook">Facebook</option>
                                <option value="YouTube">YouTube</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Discord">Discord</option>
                                <option value="Twitch">Twitch</option>
                                <option value="Medium">Medium</option>
                                <option value="Reddit">Reddit</option>
                                <option value="Dribbble">Dribbble</option>
                                <option value="Behance">Behance</option>
                                <option value="Website">Personal Website</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Profile URL *</label>
                            <input type="url" name="profile_url" class="form-control" placeholder="https://..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" name="username" class="form-control" placeholder="username">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_public" class="form-check-input" id="is_public" checked>
                            <label class="form-check-label" for="is_public">Make this link public on my profile</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes about this account"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Add Link
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
