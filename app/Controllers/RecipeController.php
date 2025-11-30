<?php

namespace App\Controllers;

use App\Core\Security;
use App\Models\Recipe;
use App\Models\PantryItem;
use App\Models\Gamification;

class RecipeController
{
    private $recipeModel;
    private $pantryModel;
    private $gamification;

    public function __construct()
    {
        $this->recipeModel = new Recipe();
        $this->pantryModel = new PantryItem();
        $this->gamification = new Gamification();
    }

    public function index()
    {
        Security::requireAuth();
        $userId = $_SESSION['user_id'];
        
        $recipes = $this->recipeModel->findByUserId($userId);
        $stats = $this->recipeModel->getStats($userId);
        $favorites = $this->recipeModel->getFavorites($userId);
        $recentlyCooked = $this->recipeModel->getRecentlyCooked($userId, 5);
        
        require __DIR__ . '/../Views/modules/recipes/index.php';
    }

    public function create()
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /recipes');
                exit;
            }

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'user_id' => $_SESSION['user_id'],
                'recipe_name' => Security::sanitizeInput($_POST['recipe_name']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'cuisine' => Security::sanitizeInput($_POST['cuisine'] ?? ''),
                'prep_time' => intval($_POST['prep_time'] ?? 0) ?: null,
                'cook_time' => intval($_POST['cook_time'] ?? 0) ?: null,
                'servings' => intval($_POST['servings'] ?? 1),
                'difficulty' => $_POST['difficulty'] ?? 'medium',
                'instructions' => Security::sanitizeInput($_POST['instructions'] ?? ''),
                'image_path' => $imagePath,
                'calories' => intval($_POST['calories'] ?? 0) ?: null,
                'source' => Security::sanitizeInput($_POST['source'] ?? ''),
                'rating' => intval($_POST['rating'] ?? 0) ?: null
            ];

            $recipeId = $this->recipeModel->create($data);
            
            if ($recipeId) {
                if (!empty($_POST['ingredients'])) {
                    foreach ($_POST['ingredients'] as $ingredient) {
                        if (!empty($ingredient['name'])) {
                            $this->recipeModel->addIngredient($recipeId, [
                                'ingredient_name' => Security::sanitizeInput($ingredient['name']),
                                'quantity' => floatval($ingredient['quantity'] ?? 0) ?: null,
                                'unit' => Security::sanitizeInput($ingredient['unit'] ?? ''),
                                'is_optional' => isset($ingredient['optional']),
                                'notes' => Security::sanitizeInput($ingredient['notes'] ?? '')
                            ]);
                        }
                    }
                }
                
                $this->gamification->addXP($_SESSION['user_id'], 20, 'recipe_added', 'Added a new recipe');
                $_SESSION['success'] = 'Recipe created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create recipe';
            }

            header('Location: /recipes');
            exit;
        }

        require __DIR__ . '/../Views/modules/recipes/create.php';
    }

    public function view($id)
    {
        Security::requireAuth();
        $recipe = $this->recipeModel->findWithIngredients($id);
        
        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Recipe not found';
            header('Location: /recipes');
            exit;
        }

        $pantryItems = $this->pantryModel->findByUserId($_SESSION['user_id']);
        $canCook = $this->checkIngredientAvailability($recipe['ingredients'], $pantryItems);

        require __DIR__ . '/../Views/modules/recipes/view.php';
    }

    public function edit($id)
    {
        Security::requireAuth();
        $recipe = $this->recipeModel->findWithIngredients($id);
        
        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Recipe not found';
            header('Location: /recipes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                $_SESSION['error'] = 'Invalid security token';
                header('Location: /recipes');
                exit;
            }

            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'recipe_name' => Security::sanitizeInput($_POST['recipe_name']),
                'description' => Security::sanitizeInput($_POST['description'] ?? ''),
                'category' => Security::sanitizeInput($_POST['category'] ?? ''),
                'cuisine' => Security::sanitizeInput($_POST['cuisine'] ?? ''),
                'prep_time' => intval($_POST['prep_time'] ?? 0) ?: null,
                'cook_time' => intval($_POST['cook_time'] ?? 0) ?: null,
                'servings' => intval($_POST['servings'] ?? 1),
                'difficulty' => $_POST['difficulty'] ?? 'medium',
                'instructions' => Security::sanitizeInput($_POST['instructions'] ?? ''),
                'image_path' => $imagePath,
                'calories' => intval($_POST['calories'] ?? 0) ?: null,
                'source' => Security::sanitizeInput($_POST['source'] ?? ''),
                'rating' => intval($_POST['rating'] ?? 0) ?: null
            ];

            if ($this->recipeModel->update($id, $data)) {
                $this->recipeModel->clearIngredients($id);
                
                if (!empty($_POST['ingredients'])) {
                    foreach ($_POST['ingredients'] as $ingredient) {
                        if (!empty($ingredient['name'])) {
                            $this->recipeModel->addIngredient($id, [
                                'ingredient_name' => Security::sanitizeInput($ingredient['name']),
                                'quantity' => floatval($ingredient['quantity'] ?? 0) ?: null,
                                'unit' => Security::sanitizeInput($ingredient['unit'] ?? ''),
                                'is_optional' => isset($ingredient['optional']),
                                'notes' => Security::sanitizeInput($ingredient['notes'] ?? '')
                            ]);
                        }
                    }
                }
                
                $_SESSION['success'] = 'Recipe updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update recipe';
            }

            header('Location: /recipes/' . $id);
            exit;
        }

        require __DIR__ . '/../Views/modules/recipes/edit.php';
    }

    public function delete($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes');
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /recipes');
            exit;
        }

        $recipe = $this->recipeModel->findById($id);
        
        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Recipe not found';
            header('Location: /recipes');
            exit;
        }

        if ($this->recipeModel->delete($id)) {
            $_SESSION['success'] = 'Recipe deleted';
        } else {
            $_SESSION['error'] = 'Failed to delete recipe';
        }

        header('Location: /recipes');
        exit;
    }

    public function toggleFavorite($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes');
            exit;
        }

        $recipe = $this->recipeModel->findById($id);
        
        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            header('Location: /recipes');
            exit;
        }

        $this->recipeModel->toggleFavorite($id);
        
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/recipes'));
        exit;
    }

    public function cook($id)
    {
        Security::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /recipes/' . $id);
            exit;
        }

        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /recipes/' . $id);
            exit;
        }

        $recipe = $this->recipeModel->findWithIngredients($id);
        
        if (!$recipe || $recipe['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Recipe not found';
            header('Location: /recipes');
            exit;
        }

        $servings = intval($_POST['servings'] ?? $recipe['servings']);
        $multiplier = $servings / $recipe['servings'];

        $deductedItems = [];
        foreach ($recipe['ingredients'] as $ingredient) {
            if ($ingredient['is_optional'] && !isset($_POST['use_' . $ingredient['id']])) {
                continue;
            }

            $pantryItem = $this->pantryModel->findByName($_SESSION['user_id'], $ingredient['ingredient_name']);
            if ($pantryItem && $ingredient['quantity']) {
                $requiredAmount = $ingredient['quantity'] * $multiplier;
                $this->pantryModel->deductQuantity($pantryItem['id'], $requiredAmount);
                $deductedItems[] = $ingredient['ingredient_name'];
            }
        }

        $this->recipeModel->markAsCooked($id);
        $this->gamification->addXP($_SESSION['user_id'], 25, 'recipe_cooked', 'Cooked: ' . $recipe['recipe_name']);
        
        if (count($deductedItems) > 0) {
            $_SESSION['success'] = 'Recipe marked as cooked! Deducted: ' . implode(', ', $deductedItems);
        } else {
            $_SESSION['success'] = 'Recipe marked as cooked!';
        }

        header('Location: /recipes/' . $id);
        exit;
    }

    private function checkIngredientAvailability($ingredients, $pantryItems)
    {
        $availability = [];
        $pantryByName = [];
        
        foreach ($pantryItems as $item) {
            $pantryByName[strtolower($item['item_name'])] = $item;
        }

        foreach ($ingredients as $ingredient) {
            $name = strtolower($ingredient['ingredient_name']);
            $available = isset($pantryByName[$name]);
            $sufficient = false;
            
            if ($available && $ingredient['quantity']) {
                $sufficient = $pantryByName[$name]['quantity'] >= $ingredient['quantity'];
            }
            
            $availability[$ingredient['id']] = [
                'available' => $available,
                'sufficient' => $available && ($sufficient || !$ingredient['quantity']),
                'pantry_quantity' => $available ? $pantryByName[$name]['quantity'] : 0,
                'pantry_unit' => $available ? $pantryByName[$name]['unit'] : ''
            ];
        }

        return $availability;
    }

    private function handleImageUpload($file)
    {
        $uploadDir = __DIR__ . '/../../uploads/recipes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('recipe_') . '.' . $ext;
        
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            return 'uploads/recipes/' . $filename;
        }
        
        return null;
    }
}
