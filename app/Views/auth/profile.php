<?php 
$pageTitle = 'Profile'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4"><i class="bi bi-person-circle me-2"></i>My Profile</h2>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Account Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/profile">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" value="<?= Security::sanitizeOutput($user['email']) ?>" disabled>
                            </div>
                            <small class="text-muted">Email cannot be changed</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= Security::sanitizeOutput($user['first_name'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= Security::sanitizeOutput($user['last_name'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Account Role</label>
                            <input type="text" class="form-control" value="<?= ucfirst(Security::sanitizeOutput($user['role'] ?? 'user')) ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control" value="<?= date('F d, Y', strtotime($user['created_at'])) ?>" disabled>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/change-password">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="8">
                            </div>
                        </div>
                        <small class="text-muted d-block mb-3">Password must be at least 8 characters long</small>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Gamification Stats</h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($stats): ?>
                    <div class="mb-4">
                        <div class="display-1 text-primary mb-2">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <h2 class="text-primary mb-0">Level <?= $stats['level'] ?? 1 ?></h2>
                        <p class="text-muted"><?= number_format($stats['total_xp'] ?? 0) ?> Total XP</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Progress to Next Level</label>
                        <?php 
                        $xpInLevel = ($stats['total_xp'] ?? 0) % 1000;
                        $progress = ($xpInLevel / 1000) * 100;
                        ?>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar" style="width: <?= $progress ?>%"></div>
                        </div>
                        <small class="text-muted"><?= $xpInLevel ?> / 1000 XP</small>
                    </div>
                    
                    <div class="row text-center mt-4">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-success mb-0"><?= $stats['current_streak'] ?? 0 ?></h4>
                                <small class="text-muted">Current Streak</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <h4 class="text-info mb-0"><?= $stats['longest_streak'] ?? 0 ?></h4>
                                <small class="text-muted">Best Streak</small>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <p class="text-muted">Start using the app to earn XP and level up!</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-award me-2"></i>Badges Earned</h5>
                </div>
                <div class="card-body">
                    <?php if ($badges && count($badges) > 0): ?>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach ($badges as $badge): ?>
                        <span class="badge bg-success p-2" title="<?= Security::sanitizeOutput($badge['badge_description'] ?? '') ?>">
                            <i class="bi bi-award me-1"></i><?= Security::sanitizeOutput($badge['badge_name']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-3">
                        <i class="bi bi-award display-4 text-muted opacity-50"></i>
                        <p class="text-muted mt-2 mb-0">No badges yet. Keep using the app!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
