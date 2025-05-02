<!-- لینک‌های سایدبار -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/sidebar.css">
<script src="<?php echo BASE_URL; ?>/assets/js/sidebar.js" defer></script>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">هیچ اعلان جدیدی ندارید</span>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="?page=logout" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>