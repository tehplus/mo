<?php
// اضافه کردن فایل‌های مورد نیاز
require_once 'includes/config.php';
require_once 'includes/classes/Database.php';
require_once 'includes/auth/functions.php';

// بررسی لاگین بودن کاربر
if (!isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

// مقادیر پیش‌فرض
$todaySales = 0;
$totalCustomers = 0;
$totalProducts = 0;
$monthlyIncome = 0;
$recentInvoices = [];
$recentNotifications = [];

// دریافت آمار کلی
try {
    
    $database = Database::getInstance();
    $conn = $database->getConnection();

    // آمار فروش امروز
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as total 
        FROM invoices 
        WHERE DATE(created_at) = CURDATE() 
        AND status = 'completed'
    ");
    $stmt->execute();
    $todaySales = $stmt->fetchColumn();

    // تعداد مشتریان فعال
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM customers 
        WHERE status = 'active'
    ");
    $stmt->execute();
    $totalCustomers = $stmt->fetchColumn();

    // تعداد محصولات موجود
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count 
        FROM products 
        WHERE status = 'active' 
        AND stock > 0
    ");
    $stmt->execute();
    $totalProducts = $stmt->fetchColumn();

    // درآمد این ماه
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as total 
        FROM invoices 
        WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
        AND YEAR(created_at) = YEAR(CURRENT_DATE())
        AND status = 'completed'
    ");
    $stmt->execute();
    $monthlyIncome = $stmt->fetchColumn();

    // آخرین فاکتورها
    $stmt = $conn->prepare("
        SELECT i.*, c.full_name as customer_name 
        FROM invoices i 
        LEFT JOIN customers c ON i.customer_id = c.id 
        ORDER BY i.created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $recentInvoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // آخرین اعلان‌ها
    $stmt = $conn->prepare("
        SELECT * FROM notifications 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $recentNotifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(Exception $e) {
    error_log("Database Error in dashboard.php: " . $e->getMessage());
}

// قالب HTML
require_once 'pages/templates/dashboard.html.php';