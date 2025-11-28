<?php 
$pageTitle = 'Profile'; 
include __DIR__ . '/../layouts/header.php'; 
use App\Core\Security;
?>

<div class="container mt-4">
    <h2>My Profile</h2>
    
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Update Profile</h5>
                    <form method="POST" action="/profile">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" value="<?= Security::sanitizeOutput($user['email']) ?>" disabled>
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
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Gamification Stats</h5>
                    <?php if ($stats): ?>
                    <div class="mb-3">
                        <h3 class="text-primary">Level <?= $stats['level'] ?></h3>
                        <p class="mb-1">Total XP: <?= $stats['total_xp'] ?></p>
                        <p class="mb-1">Current Streak: <?= $stats['current_streak'] ?> days</p>
                        <p class="mb-1">Longest Streak: <?= $stats['longest_streak'] ?> days</p>
                    </div>
                    <?php endif; ?>
                    
                    <h6 class="mt-4">Badges Earned</h6>
                    <?php if ($badges && count($badges) > 0): ?>
                        <?php foreach ($badges as $badge): ?>
                            <span class="badge bg-success me-1 mb-1"><?= Security::sanitizeOutput($badge['badge_name']) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No badges yet</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
