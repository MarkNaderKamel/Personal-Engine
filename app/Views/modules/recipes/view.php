<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <?php if ($recipe['image_path']): ?>
                    <img src="/<?= $recipe['image_path'] ?>" class="card-img-top" style="max-height: 300px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><?= htmlspecialchars($recipe['recipe_name']) ?></h4>
                    <div class="d-flex gap-2">
                        <form method="POST" action="/recipes/favorite/<?= $recipe['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                            <button type="submit" class="btn btn-<?= $recipe['is_favorite'] ? 'danger' : 'outline-danger' ?>">
                                <i class="bi bi-heart<?= $recipe['is_favorite'] ? '-fill' : '' ?>"></i>
                            </button>
                        </form>
                        <a href="/recipes/edit/<?= $recipe['id'] ?>" class="btn btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                        <a href="/recipes" class="btn btn-outline-secondary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if ($recipe['description']): ?>
                        <p class="lead"><?= htmlspecialchars($recipe['description']) ?></p>
                    <?php endif; ?>

                    <div class="row g-3 mb-4">
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-primary me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Prep Time</small>
                                    <strong><?= $recipe['prep_time'] ?? 0 ?> min</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-fire text-danger me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Cook Time</small>
                                    <strong><?= $recipe['cook_time'] ?? 0 ?> min</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-people text-success me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Servings</small>
                                    <strong><?= $recipe['servings'] ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php if ($recipe['calories']): ?>
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-lightning text-warning me-2 fs-5"></i>
                                    <div>
                                        <small class="text-muted d-block">Calories</small>
                                        <strong><?= $recipe['calories'] ?>/serving</strong>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-auto">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-trophy text-info me-2 fs-5"></i>
                                <div>
                                    <small class="text-muted d-block">Times Cooked</small>
                                    <strong><?= $recipe['times_cooked'] ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap mb-4">
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
                        <?php if ($recipe['rating']): ?>
                            <span class="badge bg-warning text-dark">
                                <?php for ($i = 0; $i < $recipe['rating']; $i++): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if ($recipe['instructions']): ?>
                        <h5><i class="bi bi-list-ol me-2"></i>Instructions</h5>
                        <div class="instructions-content">
                            <?= nl2br(htmlspecialchars($recipe['instructions'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-basket me-2"></i>Ingredients</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recipe['ingredients'])): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($recipe['ingredients'] as $ing): ?>
                                <?php 
                                $available = isset($canCook[$ing['id']]) ? $canCook[$ing['id']] : ['available' => false, 'sufficient' => false];
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="<?= $ing['is_optional'] ? 'text-muted' : '' ?>">
                                            <?= htmlspecialchars($ing['ingredient_name']) ?>
                                        </span>
                                        <?php if ($ing['is_optional']): ?>
                                            <small class="text-muted">(optional)</small>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($ing['quantity']): ?>
                                            <span class="text-muted small">
                                                <?= $ing['quantity'] ?> <?= $ing['unit'] ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($available['available']): ?>
                                            <i class="bi bi-check-circle-fill text-<?= $available['sufficient'] ? 'success' : 'warning' ?>" 
                                               title="<?= $available['sufficient'] ? 'In pantry' : 'Low quantity' ?>"></i>
                                        <?php else: ?>
                                            <i class="bi bi-x-circle text-danger" title="Not in pantry"></i>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted mb-0">No ingredients listed</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-fire me-2"></i>Cook This Recipe</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/recipes/cook/<?= $recipe['id'] ?>">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Servings to Cook</label>
                            <input type="number" class="form-control" name="servings" 
                                   value="<?= $recipe['servings'] ?>" min="1">
                        </div>

                        <p class="small text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Clicking "Cook" will deduct matching ingredients from your pantry and log this meal.
                        </p>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-fire me-1"></i>Mark as Cooked
                        </button>
                    </form>
                </div>
            </div>

            <?php if ($recipe['source']): ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <small class="text-muted">Source:</small>
                        <?php if (filter_var($recipe['source'], FILTER_VALIDATE_URL)): ?>
                            <a href="<?= htmlspecialchars($recipe['source']) ?>" target="_blank" class="d-block text-truncate">
                                <?= htmlspecialchars($recipe['source']) ?>
                            </a>
                        <?php else: ?>
                            <span class="d-block"><?= htmlspecialchars($recipe['source']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
