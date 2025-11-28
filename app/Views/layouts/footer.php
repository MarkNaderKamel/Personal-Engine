        </div>
    </main>
    
    <?php if (\App\Core\Security::isAuthenticated()): ?>
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 mb-3 mb-md-0">
                    <h5 class="mb-3">
                        <i class="bi bi-globe-americas me-2"></i>Life Atlas
                    </h5>
                    <p class="text-muted small mb-0">Your all-in-one personal life management platform. Organize, track, and optimize every aspect of your life.</p>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <h6 class="text-uppercase mb-3">Quick Links</h6>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="/dashboard" class="text-muted text-decoration-none small">Dashboard</a></li>
                                <li class="mb-2"><a href="/tasks" class="text-muted text-decoration-none small">Tasks</a></li>
                                <li class="mb-2"><a href="/bills" class="text-muted text-decoration-none small">Bills</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="/goals" class="text-muted text-decoration-none small">Goals</a></li>
                                <li class="mb-2"><a href="/analytics" class="text-muted text-decoration-none small">Analytics</a></li>
                                <li class="mb-2"><a href="/ai-assistant" class="text-muted text-decoration-none small">AI Assistant</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6 class="text-uppercase mb-3">Your Stats</h6>
                    <div class="d-flex gap-4">
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-primary">
                                <?php
                                $gamification = new \App\Models\Gamification();
                                $stats = $gamification->getUserStats($_SESSION['user_id'] ?? 0);
                                echo $stats['level'] ?? 1;
                                ?>
                            </div>
                            <small class="text-muted">Level</small>
                        </div>
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-success"><?= $stats['current_streak'] ?? 0 ?></div>
                            <small class="text-muted">Day Streak</small>
                        </div>
                        <div class="text-center">
                            <div class="fs-4 fw-bold text-warning"><?= number_format($stats['total_xp'] ?? 0) ?></div>
                            <small class="text-muted">Total XP</small>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <small class="text-muted">&copy; <?= date('Y') ?> Life Atlas. All rights reserved.</small>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-muted">Made with <i class="bi bi-heart-fill text-danger"></i> for productivity</small>
                </div>
            </div>
        </div>
    </footer>
    <?php endif; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
    
    <?php if (isset($pageScripts)): ?>
    <?php foreach ($pageScripts as $script): ?>
    <script src="<?= $script ?>"></script>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inlineScript)): ?>
    <script><?= $inlineScript ?></script>
    <?php endif; ?>
</body>
</html>
