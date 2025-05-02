<?php
/**
 * API دسته‌بندی‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 09:28:47
 */

// بررسی دسترسی
if (!checkUserPermission('manage_categories')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'شما دسترسی لازم را ندارید']);
    exit;
}

// لود کردن توابع مورد نیاز
require_once BASE_PATH . '/includes/db/categories.php';

// دریافت متد درخواست
$method = $_SERVER['REQUEST_METHOD'];

// دریافت اکشن از URL
$action = isset($_GET['action']) ? $_GET['action'] : '';

// پردازش درخواست
switch ($method) {
    case 'GET':
        switch ($action) {
            case 'list':
                $categories = getAllCategories();
                if ($categories !== false) {
                    echo json_encode([
                        'success' => true,
                        'categories' => $categories
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'خطا در دریافت لیست دسته‌بندی‌ها'
                    ]);
                }
                break;
                
            case 'get':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                if ($id > 0) {
                    $category = getCategoryById($id);
                    if ($category) {
                        echo json_encode([
                            'success' => true,
                            'category' => $category
                        ]);
                    } else {
                        http_response_code(404);
                        echo json_encode([
                            'success' => false,
                            'message' => 'دسته‌بندی مورد نظر یافت نشد'
                        ]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'شناسه دسته‌بندی نامعتبر است'
                    ]);
                }
                break;
                
            case 'stats':
                $stats = getCategoriesStats();
                if ($stats !== false) {
                    echo json_encode([
                        'success' => true,
                        'stats' => $stats
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'خطا در دریافت آمار دسته‌بندی‌ها'
                    ]);
                }
                break;
                
            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'اکشن نامعتبر است'
                ]);
        }
        break;
        
    case 'POST':
        switch ($action) {
            case 'create':
                // دریافت داده‌های ارسالی
                $input = json_decode(file_get_contents('php://input'), true);
                
                // اعتبارسنجی داده‌ها
                if (!isset($input['name']) || empty(trim($input['name']))) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'نام دسته‌بندی الزامی است'
                    ]);
                    exit;
                }
                
                // آماده‌سازی داده‌ها
                $data = [
                    'name' => trim($input['name']),
                    'parent_id' => isset($input['parent_id']) ? (int)$input['parent_id'] : 0,
                    'description' => isset($input['description']) ? trim($input['description']) : '',
                    'status' => isset($input['status']) ? (bool)$input['status'] : true
                ];
                
                // ذخیره دسته‌بندی
                if (createCategory($data)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'دسته‌بندی با موفقیت ایجاد شد'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'خطا در ایجاد دسته‌بندی'
                    ]);
                }
                break;
                
            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'اکشن نامعتبر است'
                ]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'متد درخواست نامعتبر است'
        ]);
}