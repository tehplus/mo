<?php

/**
 * مسیریاب اصلی برنامه
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

session_start();
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
}
date_default_timezone_set('Asia/Tehran');

// تنظیمات پایه
define('BASE_PATH', __DIR__);
define('BASE_URL', '/mo');
define('APP_NAME', 'سیستم حسابداری هوشمند');
define('APP_VERSION', '1.0.0');

// متغیرهای سراسری برای قالب
$meta = [
    'title' => APP_NAME,
    'description' => 'سیستم حسابداری آنلاین برای کسب و کارهای کوچک و متوسط'
];

// لیست صفحات مجاز برای دسترسی عمومی
$public_pages = ['home', 'register', 'login'];

// دریافت صفحه درخواستی
$page = isset($_GET['page']) ? strtolower(trim($_GET['page'])) : 'home';

// اگر کاربر لاگین نکرده و صفحه عمومی نیست
if (!in_array($page, $public_pages) && !isset($_SESSION['user_id'])) {
    header('Location: ?page=login');
    exit;
}

// حذف کاراکترهای غیرمجاز از نام صفحه
$page = preg_replace('/[^a-z0-9\-_]/', '', $page);

// اگر صفحه خالی شد
if (empty($page)) {
    $page = 'home';
}

// بررسی نوع صفحه
$is_public = in_array($page, $public_pages);

// نمایش قالب مناسب
if ($is_public) {
    // صفحات عمومی
    switch ($page) {
        case 'home':
            require_once 'pages/home.php';
            break;
            
        case 'register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                require_once 'includes/config.php';
                require_once 'includes/classes/Database.php';
                require_once 'includes/auth/register_handler.php';
                exit;
            } else if (file_exists('pages/auth/register.php')) {
                require_once 'pages/auth/register.php';
            } else {
                die('خطا: صفحه ثبت نام یافت نشد.');
            }
            break;
            
        case 'login':
            if (file_exists('pages/auth/login.php')) {
                require_once 'pages/auth/login.php';
            } else {
                die('خطا: صفحه ورود یافت نشد.');
            }
            break;
    }
} else {
    // صفحات داشبورد
    include_once 'includes/header.php';
    include_once 'includes/sidebar.php';
    
    echo '<div class="content-wrapper">';
    
    $page_file = "pages/$page/index.php";
    if (file_exists($page_file)) {
        require_once $page_file;
    } else {
        echo '<div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">صفحه مورد نظر یافت نشد</h1>
                        </div>
                    </div>
                </div>
            </div>';
    }
    
    echo '</div>';
    
    include_once 'includes/footer.php';
}