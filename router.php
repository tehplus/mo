<?php
session_start();
date_default_timezone_set('Asia/Tehran');

// مسیر اصلی پروژه
define('BASE_PATH', __DIR__);

// تنظیمات پایه
$config = [
    'default_page' => 'dashboard',
    'auth_pages' => ['login', 'register', 'forgot-password'],
    'require_auth' => true
];

// دریافت صفحه درخواستی
$page = isset($_GET['page']) ? $_GET['page'] : $config['default_page'];

// بررسی دسترسی و احراز هویت
if ($config['require_auth'] && !in_array($page, $config['auth_pages']) && !isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

// شامل کردن هدر
if (!in_array($page, $config['auth_pages'])) {
    include_once 'includes/header.php';
    include_once 'includes/sidebar.php';
}

// مسیریابی صفحات
switch ($page) {
    case 'login':
        require_once 'pages/auth/login.php';
        break;
        
    case 'register':
        require_once 'pages/auth/register.php';
        break;
        
    case 'dashboard':
        require_once 'pages/dashboard/index.php';
        break;
        
    case 'profile':
        require_once 'pages/profile/index.php';
        break;
        
    case 'transactions':
        require_once 'pages/transactions/index.php';
        break;
        
    case 'invoices':
        require_once 'pages/invoices/index.php';
        break;
        
    case 'reports':
        require_once 'pages/reports/index.php';
        break;
        
    case 'settings':
        require_once 'pages/settings/index.php';
        break;
        
    default:
        // صفحه 404 یا صفحه خالی
        echo '<div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">صفحه مورد نظر یافت نشد</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        break;
}

// شامل کردن فوتر
if (!in_array($page, $config['auth_pages'])) {
    include_once 'includes/footer.php';
}
?>