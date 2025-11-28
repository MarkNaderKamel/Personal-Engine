        </div>
    </main>
    
    <?php if (\App\Core\Security::isAuthenticated()): ?>
    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="/dashboard" class="mobile-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0 ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>
            <span>Dashboard</span>
        </a>
        <a href="/tasks" class="mobile-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/tasks') === 0 ? 'active' : '' ?>">
            <i class="bi bi-list-task"></i>
            <span>Tasks</span>
        </a>
        <a href="/transactions" class="mobile-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/transactions') === 0 ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i>
            <span>Finance</span>
        </a>
        <a href="/goals" class="mobile-nav-item <?= strpos($_SERVER['REQUEST_URI'], '/goals') === 0 ? 'active' : '' ?>">
            <i class="bi bi-bullseye"></i>
            <span>Goals</span>
        </a>
        <button class="mobile-nav-item" id="mobileMenuBtn">
            <i class="bi bi-grid-3x3-gap"></i>
            <span>More</span>
        </button>
    </nav>
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
