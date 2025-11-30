<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Recipe</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/recipes/edit/<?= $recipe['id'] ?>" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Recipe Name *</label>
                                <input type="text" class="form-control" name="recipe_name" 
                                       value="<?= htmlspecialchars($recipe['recipe_name']) ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <?php 
                                    $categories = ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Snack', 'Appetizer', 'Soup', 'Salad', 'Beverage', 'Other'];
                                    foreach ($categories as $cat): ?>
                                        <option value="<?= $cat ?>" <?= $recipe['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"><?= htmlspecialchars($recipe['description'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Cuisine</label>
                                <input type="text" class="form-control" name="cuisine" value="<?= htmlspecialchars($recipe['cuisine'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Difficulty</label>
                                <select class="form-select" name="difficulty">
                                    <option value="easy" <?= $recipe['difficulty'] === 'easy' ? 'selected' : '' ?>>Easy</option>
                                    <option value="medium" <?= $recipe['difficulty'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option value="hard" <?= $recipe['difficulty'] === 'hard' ? 'selected' : '' ?>>Hard</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Servings</label>
                                <input type="number" class="form-control" name="servings" value="<?= $recipe['servings'] ?>" min="1">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Prep Time (min)</label>
                                <input type="number" class="form-control" name="prep_time" value="<?= $recipe['prep_time'] ?>" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cook Time (min)</label>
                                <input type="number" class="form-control" name="cook_time" value="<?= $recipe['cook_time'] ?>" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Calories</label>
                                <input type="number" class="form-control" name="calories" value="<?= $recipe['calories'] ?>" min="0">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ingredients</label>
                                <div id="ingredientsList">
                                    <?php if (!empty($recipe['ingredients'])): ?>
                                        <?php foreach ($recipe['ingredients'] as $i => $ing): ?>
                                            <div class="ingredient-row row g-2 mb-2">
                                                <div class="col-5">
                                                    <input type="text" class="form-control" name="ingredients[<?= $i ?>][name]" 
                                                           value="<?= htmlspecialchars($ing['ingredient_name']) ?>" placeholder="Ingredient name">
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" class="form-control" name="ingredients[<?= $i ?>][quantity]" 
                                                           value="<?= $ing['quantity'] ?>" placeholder="Qty" step="0.1">
                                                </div>
                                                <div class="col-2">
                                                    <input type="text" class="form-control" name="ingredients[<?= $i ?>][unit]" 
                                                           value="<?= htmlspecialchars($ing['unit'] ?? '') ?>" placeholder="Unit">
                                                </div>
                                                <div class="col-2">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" name="ingredients[<?= $i ?>][optional]"
                                                               <?= $ing['is_optional'] ? 'checked' : '' ?>>
                                                        <label class="form-check-label small">Optional</label>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeIngredient(this)">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addIngredient()">
                                    <i class="bi bi-plus me-1"></i>Add Ingredient
                                </button>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Instructions</label>
                                <textarea class="form-control" name="instructions" rows="6"><?= htmlspecialchars($recipe['instructions'] ?? '') ?></textarea>
                            </div>

                            <?php if ($recipe['image_path']): ?>
                                <div class="col-12">
                                    <label class="form-label">Current Image</label>
                                    <div><img src="/<?= $recipe['image_path'] ?>" class="img-thumbnail" style="max-height: 150px;"></div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-6">
                                <label class="form-label">Update Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Source</label>
                                <input type="text" class="form-control" name="source" value="<?= htmlspecialchars($recipe['source'] ?? '') ?>">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rating</label>
                                <select class="form-select" name="rating">
                                    <option value="">Not rated</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>" <?= $recipe['rating'] == $i ? 'selected' : '' ?>><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Update Recipe
                            </button>
                            <a href="/recipes/<?= $recipe['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let ingredientIndex = <?= count($recipe['ingredients'] ?? []) ?>;

function addIngredient() {
    const html = `
        <div class="ingredient-row row g-2 mb-2">
            <div class="col-5">
                <input type="text" class="form-control" name="ingredients[${ingredientIndex}][name]" placeholder="Ingredient name">
            </div>
            <div class="col-2">
                <input type="number" class="form-control" name="ingredients[${ingredientIndex}][quantity]" placeholder="Qty" step="0.1">
            </div>
            <div class="col-2">
                <input type="text" class="form-control" name="ingredients[${ingredientIndex}][unit]" placeholder="Unit">
            </div>
            <div class="col-2">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="ingredients[${ingredientIndex}][optional]">
                    <label class="form-check-label small">Optional</label>
                </div>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeIngredient(this)">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
    `;
    document.getElementById('ingredientsList').insertAdjacentHTML('beforeend', html);
    ingredientIndex++;
}

function removeIngredient(btn) {
    btn.closest('.ingredient-row').remove();
}
</script>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>
