<?php
/**
 * توابع پایگاه داده مربوط به دسته‌بندی‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 09:28:47
 */

/**
 * دریافت همه دسته‌بندی‌ها
 */
function getAllCategories() {
    global $db;
    
    try {
        $query = "SELECT 
                    id,
                    parent_id,
                    name,
                    description,
                    status,
                    created_at,
                    updated_at,
                    (SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count
                FROM categories
                ORDER BY parent_id ASC, name ASC";
                
        $stmt = $db->prepare($query);
        $stmt->execute();
        
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // تبدیل به ساختار درختی برای jstree
        return formatCategoriesForJsTree($categories);
        
    } catch (PDOException $e) {
        error_log("Error in getAllCategories: " . $e->getMessage());
        return false;
    }
}

/**
 * دریافت اطلاعات یک دسته‌بندی
 */
function getCategoryById($id) {
    global $db;
    
    try {
        $query = "SELECT 
                    c.*,
                    (SELECT COUNT(*) FROM products WHERE category_id = c.id) as products_count,
                    p.name as parent_name
                FROM categories c
                LEFT JOIN categories p ON c.parent_id = p.id
                WHERE c.id = :id";
                
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Error in getCategoryById: " . $e->getMessage());
        return false;
    }
}

/**
 * ایجاد دسته‌بندی جدید
 */
function createCategory($data) {
    global $db;
    
    try {
        $query = "INSERT INTO categories (
                    parent_id,
                    name,
                    description,
                    status,
                    created_at,
                    updated_at
                ) VALUES (
                    :parent_id,
                    :name,
                    :description,
                    :status,
                    NOW(),
                    NOW()
                )";
                
        $stmt = $db->prepare($query);
        
        $stmt->bindParam(':parent_id', $data['parent_id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_BOOL);
        
        return $stmt->execute();
        
    } catch (PDOException $e) {
        error_log("Error in createCategory: " . $e->getMessage());
        return false;
    }
}

/**
 * دریافت آمار دسته‌بندی‌ها
 */
function getCategoriesStats() {
    global $db;
    
    try {
        $stats = [
            'main_categories' => 0,
            'sub_categories' => 0,
            'total_products' => 0,
            'inactive_categories' => 0
        ];
        
        // تعداد دسته‌های اصلی
        $query = "SELECT COUNT(*) FROM categories WHERE parent_id = 0 OR parent_id IS NULL";
        $stmt = $db->query($query);
        $stats['main_categories'] = $stmt->fetchColumn();
        
        // تعداد زیردسته‌ها
        $query = "SELECT COUNT(*) FROM categories WHERE parent_id > 0";
        $stmt = $db->query($query);
        $stats['sub_categories'] = $stmt->fetchColumn();
        
        // تعداد کل محصولات
        $query = "SELECT COUNT(*) FROM products";
        $stmt = $db->query($query);
        $stats['total_products'] = $stmt->fetchColumn();
        
        // تعداد دسته‌های غیرفعال
        $query = "SELECT COUNT(*) FROM categories WHERE status = 0";
        $stmt = $db->query($query);
        $stats['inactive_categories'] = $stmt->fetchColumn();
        
        return $stats;
        
    } catch (PDOException $e) {
        error_log("Error in getCategoriesStats: " . $e->getMessage());
        return false;
    }
}

/**
 * تبدیل آرایه دسته‌بندی‌ها به ساختار درختی برای jstree
 */
function formatCategoriesForJsTree($categories) {
    $tree = [];
    
    foreach ($categories as $category) {
        $node = [
            'id' => $category['id'],
            'parent' => $category['parent_id'] ? $category['parent_id'] : '#',
            'text' => $category['name'],
            'data' => [
                'description' => $category['description'],
                'status' => $category['status'],
                'products_count' => $category['products_count'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at']
            ],
            'state' => [
                'opened' => true
            ],
            'icon' => 'fas fa-folder' . ($category['status'] ? '' : ' text-muted')
        ];
        
        $tree[] = $node;
    }
    
    return $tree;
}