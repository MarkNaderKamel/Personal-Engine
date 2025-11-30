<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="bi bi-book me-2"></i>Recipe Book</h1>
            <p class="text-muted mb-0">Manage your recipes and meal planning</p>
        </div>
        <a href="/recipes/create" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Add Recipe
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Total Recipes</p>
                            <h3 class="mb-0"><?= $stats['total_recipes'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-journal-richtext display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Favorites</p>
                            <h3 class="mb-0"><?= $stats['favorites_count'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-heart-fill display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Times Cooked</p>
                            <h3 class="mb-0"><?= $stats['total_times_cooked'] ?? 0 ?></h3>
                        </div>
                        <i class="bi bi-fire display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1">Avg Rating</p>
                            <h3 class="mb-0"><?= number_format($stats['average_rating'] ?? 0, 1) ?> <small class="fs-6">/5</small></h3>
                        </div>
                        <i class="bi bi-star-fill display-6 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($recentlyCooked)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Recently Cooked</h6>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3 overflow-auto pb-2">
                    <?php foreach ($recentlyCooked as $recipe): ?>
                        <a href="/recipes/<?= $recipe['id'] ?>" class="text-decoration-none">
                            <div class="recent-recipe-card p-3 border rounded bg-light text-center" style="min-width: 120px;">
                                <i class="bi bi-journal-text fs-3 text-primary"></i>
                                <p class="mb-0 mt-2 small text-dark text-truncate" style="max-width: 100px;">
                                    <?= htmlspecialchars($recipe['recipe_name']) ?>
                                </p>
                                <small class="text-muted"><?= date('M j', strtotime($recipe['last_cooked'])) ?></small>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>All Recipes</h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchRecipes" 
                       style="max-width: 200px;" placeholder="Search...">
                <select class="form-select form-select-sm" id="filterCategory" style="max-width: 150px;">
                    <option value="">All Categories</option>
                    <option value="Breakfast">Breakfast</option>
                    <option value="Lunch">Lunch</option>
                    <option value="Dinner">Dinner</option>
                    <option value="Dessert">Dessert</option>
                    <option value="Snack">Snack</option>
                    <option value="Appetizer">Appetizer</option>
                    <option value="Soup">Soup</option>
                    <option value="Salad">Salad</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($recipes)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-journal-x display-1 text-muted"></i>
                    <h4 class="mt-3">No Recipes Yet</h4>
                    <p class="text-muted">Start building your recipe collection</p>
                    <a href="/recipes/create" class="btn btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add First Recipe
                    </a>
                </div>
            <?php else: ?>
                <div class="row g-4" id="recipesGrid">
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="col-md-6 col-lg-4 recipe-card" 
                             data-name="<?= strtolower($recipe['recipe_name']) ?>"
                             data-category="<?= $recipe['category'] ?>">
                            <div class="card h-100 recipe-item">
                                <?php if ($recipe['image_path']): ?>
                                    <img src="/<?= $recipe['image_path'] ?>" class="card-img-top" style="height: 160px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                        <i class="bi bi-egg-fried text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0"><?= htmlspecialchars($recipe['recipe_name']) ?></h6>
                                        <form method="POST" action="/recipes/favorite/<?= $recipe['id'] ?>" style="display: inline;">
                                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                            <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent">
                                                <i class="bi bi-heart<?= $recipe['is_favorite'] ? '-fill text-danger' : '' ?> fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="card-text small text-muted mb-2">
                                        <?= htmlspecialchars(substr($recipe['description'] ?? 'No description', 0, 80)) ?>...
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap mb-2">
                                        <?php if ($recipe['category']): ?>
                                            <span class="badge bg-primary"><?= htmlspecialchars($recipe['category']) ?></span>
                                        <?php endif; ?>
                                        <?php if ($recipe['cuisine']): ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($recipe['cuisine']) ?></span>
                                        <?php endif; ?>
                                        <?php if ($recipe['difficulty']): ?>
                                            <span class="badge bg-<?= $recipe['difficulty'] === 'easy' ? 'success' : ($recipe['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                                <?= ucfirst($recipe['difficulty']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><i class="bi bi-clock me-1"></i><?= ($recipe['prep_time'] ?? 0) + ($recipe['cook_time'] ?? 0) ?> min</span>
                                        <span><i class="bi bi-people me-1"></i><?= $recipe['servings'] ?> servings</span>
                                        <span><i class="bi bi-fire me-1"></i><?= $recipe['times_cooked'] ?>x</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex gap-2">
                                        <a href="/recipes/<?= $recipe['id'] ?>" class="btn btn-primary btn-sm flex-fill">View</a>
                                        <a href="/recipes/edit/<?= $recipe['id'] ?>" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="/recipes/delete/<?= $recipe['id'] ?>" 
                                              onsubmit="return confirm('Delete this recipe?')">
                                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function filterRecipes() {
    const search = document.getElementById('searchRecipes').value.toLowerCase();
    const category = document.getElementById('filterCategory').value;
    
    document.querySelectorAll('.recipe-card').forEach(card => {
        const name = card.dataset.name;
        const cat = card.dataset.category;
        const matchesSearch = name.includes(search);
        const matchesCategory = !category || cat === category;
        card.style.display = matchesSearch && matchesCategory ? '' : 'none';
    });
}

document.getElementById('searchRecipes').addEventListener('input', filterRecipes);
document.getElementById('filterCategory').addEventListener('change', filterRecipes);
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
