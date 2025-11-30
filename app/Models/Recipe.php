<?php

namespace App\Models;

use App\Core\Model;

class Recipe extends Model
{
    protected $table = 'recipes';

    public function findByUserId($userId, $orderBy = 'recipe_name ASC', $limit = null)
    {
        $sql = "SELECT r.*, 
                   (SELECT COUNT(*) FROM recipe_ingredients WHERE recipe_id = r.id) as ingredient_count
            FROM {$this->table} r
            WHERE r.user_id = ? 
            ORDER BY r.{$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findWithIngredients($id)
    {
        $recipe = $this->findById($id);
        if ($recipe) {
            $stmt = $this->db->prepare("
                SELECT * FROM recipe_ingredients 
                WHERE recipe_id = ?
                ORDER BY id ASC
            ");
            $stmt->execute([$id]);
            $recipe['ingredients'] = $stmt->fetchAll();
        }
        return $recipe;
    }

    public function findByCategory($userId, $category)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND category = ?
            ORDER BY recipe_name ASC
        ");
        $stmt->execute([$userId, $category]);
        return $stmt->fetchAll();
    }

    public function getFavorites($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND is_favorite = true
            ORDER BY recipe_name ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getRecentlyCooked($userId, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND last_cooked IS NOT NULL
            ORDER BY last_cooked DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function getMostCooked($userId, $limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? AND times_cooked > 0
            ORDER BY times_cooked DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public function getStats($userId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total_recipes,
                COUNT(CASE WHEN is_favorite THEN 1 END) as favorites_count,
                SUM(times_cooked) as total_times_cooked,
                AVG(rating) as average_rating
            FROM {$this->table} 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function search($userId, $query)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE user_id = ? 
            AND (
                LOWER(recipe_name) LIKE LOWER(?) OR
                LOWER(description) LIKE LOWER(?) OR
                LOWER(cuisine) LIKE LOWER(?)
            )
            ORDER BY recipe_name ASC
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([$userId, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    public function toggleFavorite($id)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET is_favorite = NOT is_favorite,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function markAsCooked($id)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} 
            SET times_cooked = times_cooked + 1,
                last_cooked = CURRENT_DATE,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} 
            (user_id, recipe_name, description, category, cuisine, prep_time, cook_time,
             servings, difficulty, instructions, image_path, calories, source, rating)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        
        $stmt->execute([
            $data['user_id'],
            $data['recipe_name'],
            $data['description'] ?? null,
            $data['category'] ?? null,
            $data['cuisine'] ?? null,
            $data['prep_time'] ?? null,
            $data['cook_time'] ?? null,
            $data['servings'] ?? 1,
            $data['difficulty'] ?? 'medium',
            $data['instructions'] ?? null,
            $data['image_path'] ?? null,
            $data['calories'] ?? null,
            $data['source'] ?? null,
            $data['rating'] ?? null
        ]);
        
        return $stmt->fetch()['id'];
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE {$this->table} SET
                recipe_name = ?,
                description = ?,
                category = ?,
                cuisine = ?,
                prep_time = ?,
                cook_time = ?,
                servings = ?,
                difficulty = ?,
                instructions = ?,
                image_path = COALESCE(?, image_path),
                calories = ?,
                source = ?,
                rating = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['recipe_name'],
            $data['description'] ?? null,
            $data['category'] ?? null,
            $data['cuisine'] ?? null,
            $data['prep_time'] ?? null,
            $data['cook_time'] ?? null,
            $data['servings'] ?? 1,
            $data['difficulty'] ?? 'medium',
            $data['instructions'] ?? null,
            $data['image_path'] ?? null,
            $data['calories'] ?? null,
            $data['source'] ?? null,
            $data['rating'] ?? null,
            $id
        ]);
    }

    public function addIngredient($recipeId, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO recipe_ingredients 
            (recipe_id, ingredient_name, quantity, unit, is_optional, notes)
            VALUES (?, ?, ?, ?, ?, ?)
            RETURNING id
        ");
        
        $stmt->execute([
            $recipeId,
            $data['ingredient_name'],
            $data['quantity'] ?? null,
            $data['unit'] ?? null,
            $data['is_optional'] ?? false,
            $data['notes'] ?? null
        ]);
        
        return $stmt->fetch()['id'];
    }

    public function removeIngredient($ingredientId)
    {
        $stmt = $this->db->prepare("DELETE FROM recipe_ingredients WHERE id = ?");
        return $stmt->execute([$ingredientId]);
    }

    public function clearIngredients($recipeId)
    {
        $stmt = $this->db->prepare("DELETE FROM recipe_ingredients WHERE recipe_id = ?");
        return $stmt->execute([$recipeId]);
    }

    public function getIngredients($recipeId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM recipe_ingredients 
            WHERE recipe_id = ?
            ORDER BY id ASC
        ");
        $stmt->execute([$recipeId]);
        return $stmt->fetchAll();
    }
}
