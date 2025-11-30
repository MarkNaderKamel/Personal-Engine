<?php require __DIR__ . '/../../layouts/header.php'; ?>

<div class="content-wrapper">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Recipe</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/recipes/create" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= \App\Core\Security::generateCSRFToken() ?>">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Recipe Name *</label>
                                <input type="text" class="form-control" name="recipe_name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">Select...</option>
                                    <option value="Breakfast">Breakfast</option>
                                    <option value="Lunch">Lunch</option>
                                    <option value="Dinner">Dinner</option>
                                    <option value="Dessert">Dessert</option>
                                    <option value="Snack">Snack</option>
                                    <option value="Appetizer">Appetizer</option>
                                    <option value="Soup">Soup</option>
                                    <option value="Salad">Salad</option>
                                    <option value="Beverage">Beverage</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="2"></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Cuisine</label>
                                <input type="text" class="form-control" name="cuisine" placeholder="e.g., Italian, Mexican">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Difficulty</label>
                                <select class="form-select" name="difficulty">
                                    <option value="easy">Easy</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Servings</label>
                                <input type="number" class="form-control" name="servings" value="4" min="1">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Prep Time (min)</label>
                                <input type="number" class="form-control" name="prep_time" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cook Time (min)</label>
                                <input type="number" class="form-control" name="cook_time" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Calories</label>
                                <input type="number" class="form-control" name="calories" min="0" placeholder="per serving">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ingredients</label>
                                <div id="ingredientsList">
                                    <div class="ingredient-row row g-2 mb-2">
                                        <div class="col-5">
                                            <input type="text" class="form-control" name="ingredients[0][name]" placeholder="Ingredient name">
                                        </div>
                                        <div class="col-2">
                                            <input type="number" class="form-control" name="ingredients[0][quantity]" placeholder="Qty" step="0.1">
                                        </div>
                                        <div class="col-2">
                                            <input type="text" class="form-control" name="ingredients[0][unit]" placeholder="Unit">
                                        </div>
                                        <div class="col-2">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="ingredients[0][optional]">
                                                <label class="form-check-label small">Optional</label>
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeIngredient(this)">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addIngredient()">
                                    <i class="bi bi-plus me-1"></i>Add Ingredient
                                </button>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Instructions</label>
                                <textarea class="form-control" name="instructions" rows="6" placeholder="Step-by-step cooking instructions..."></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Recipe Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Source</label>
                                <input type="text" class="form-control" name="source" placeholder="URL or cookbook name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rating</label>
                                <select class="form-select" name="rating">
                                    <option value="">Not rated</option>
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Save Recipe
                            </button>
                            <a href="/recipes" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let ingredientIndex = 1;

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
