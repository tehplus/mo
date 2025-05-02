<?php
/**
 * پردازش درخواست‌های مربوط به دسته‌بندی‌ها
 */

// شروع سشن
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// بررسی دسترسی
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode([
        'success' => false,
        'message' => 'دسترسی غیرمجاز'
    ]));
}

// تنظیم هدر JSON
header('Content-Type: application/json; charset=utf-8');

// دریافت عملیات درخواستی
$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'getTree':
            // فعلاً داده تستی برمیگردونیم
            echo json_encode([
                [
                    'id' => '1',
                    'text' => 'موبایل',
                    'children' => [
                        ['id' => '2', 'text' => 'سامسونگ'],
                        ['id' => '3', 'text' => 'اپل']
                    ]
                ],
                [
                    'id' => '4',
                    'text' => 'لپ تاپ',
                    'children' => [
                        ['id' => '5', 'text' => 'ایسوس'],
                        ['id' => '6', 'text' => 'لنوو']
                    ]
                ]
            ]);
            break;

        case 'getStats':
            // آمار تستی
            echo json_encode([
                'success' => true,
                'data' => [
                    'mainCategories' => 2,
                    'subCategories' => 4,
                    'totalProducts' => 150,
                    'totalCategories' => 6,
                    'activeCategories' => 5
                ]
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'عملیات نامعتبر'
            ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'خطای سرور'
    ]);
}