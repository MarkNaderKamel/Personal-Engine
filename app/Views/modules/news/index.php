<?php 
$pageTitle = 'News'; 
include __DIR__ . '/../../layouts/header.php'; 
use App\Core\Security;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-title">
        <i class="bi bi-newspaper"></i>
        <h2 class="mb-0">News Feed</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-funnel me-2 text-primary"></i>Categories</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($data['categories'] as $category): ?>
                    <a href="/news?category=<?= $category ?>" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= $data['currentCategory'] === $category ? 'active' : '' ?>">
                        <span>
                            <?php
                            $icons = [
                                'general' => 'globe',
                                'technology' => 'cpu',
                                'business' => 'briefcase',
                                'health' => 'heart-pulse',
                                'science' => 'rocket',
                                'sports' => 'trophy',
                                'entertainment' => 'film'
                            ];
                            ?>
                            <i class="bi bi-<?= $icons[$category] ?? 'newspaper' ?> me-2"></i>
                            <?= ucfirst($category) ?>
                        </span>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bi bi-<?= $icons[$data['currentCategory']] ?? 'newspaper' ?> me-2 text-primary"></i>
                <?= ucfirst($data['currentCategory']) ?> News
            </h5>
            <span class="badge bg-primary"><?= count($data['articles']) ?> Articles</span>
        </div>
        
        <?php if (count($data['articles']) > 0): ?>
        <div class="row">
            <?php foreach ($data['articles'] as $index => $article): ?>
            <div class="col-12 mb-4">
                <div class="card hover-lift">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary-soft text-primary me-2"><?= Security::sanitizeOutput($article['source']) ?></span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i><?= $article['date'] ?>
                                    </small>
                                </div>
                                <h5 class="card-title mb-2">
                                    <a href="<?= $article['url'] ?>" class="text-decoration-none text-dark">
                                        <?= Security::sanitizeOutput($article['title']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted mb-3"><?= Security::sanitizeOutput($article['description']) ?></p>
                                <div class="d-flex gap-2">
                                    <a href="<?= $article['url'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-right me-1"></i>Read More
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="shareArticle('<?= addslashes($article['title']) ?>')">
                                        <i class="bi bi-share"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="bookmarkArticle(this)">
                                        <i class="bi bi-bookmark"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3 d-none d-md-flex align-items-center justify-content-center">
                                <div class="bg-light rounded p-4 text-center" style="min-width: 100px;">
                                    <i class="bi bi-<?= $icons[$data['currentCategory']] ?? 'newspaper' ?> text-primary" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body empty-state">
                <i class="bi bi-newspaper text-muted"></i>
                <h5>No Articles Found</h5>
                <p class="text-muted">There are no articles in this category at the moment. Check back later for updates.</p>
                <a href="/news?category=general" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>View General News
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($data['isLive']): ?>
<div class="alert alert-success mt-4">
    <i class="bi bi-check-circle me-2"></i>
    <strong>Live News:</strong> Showing real-time articles from Hacker News. Last updated at <?= $data['lastUpdated'] ?>.
</div>
<?php else: ?>
<div class="alert alert-warning mt-4">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Offline Mode:</strong> Unable to fetch live news. Showing sample articles. Please check your internet connection.
</div>
<?php endif; ?>

<script>
function shareArticle(title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: 'Check out this article: ' + title,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        if (typeof showToast === 'function') {
            showToast('Link copied to clipboard!', 'success');
        }
    }
}

function bookmarkArticle(btn) {
    const icon = btn.querySelector('i');
    icon.classList.toggle('bi-bookmark');
    icon.classList.toggle('bi-bookmark-fill');
    
    if (icon.classList.contains('bi-bookmark-fill')) {
        if (typeof showToast === 'function') {
            showToast('Article bookmarked!', 'success');
        }
    }
}
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
