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
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سیستم حسابداری</title>
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- AdminLTE RTL -->
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.rtl.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<?php if (!in_array($page, $config['auth_pages'])): ?>
<div class="wrapper">
    <?php 
    include_once 'includes/header.php';
    include_once 'includes/sidebar.php';
    ?>
    
    <!-- محتوای اصلی -->
    <div class="content-wrapper">
<?php endif; ?>

<?php
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
        echo '<div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">صفحه مورد نظر یافت نشد</h1>
                        </div>
                    </div>
                </div>
            </div>';
        break;
}
?>

<?php if (!in_array($page, $config['auth_pages'])): ?>
    </div><!-- /.content-wrapper -->
    <?php include_once 'includes/footer.php'; ?>
</div><!-- /.wrapper -->
<?php endif; ?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html>