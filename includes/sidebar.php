<?php
// دریافت صفحه فعلی
$current_page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="<?php echo APP_NAME; ?>">
            <span class="logo-text"><?php echo APP_NAME; ?></span>
        </div>
        <button id="sidebar-toggle" class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="sidebar-user">
        <div class="user-image">
            <img src="<?php echo BASE_URL; ?>/assets/images/user-avatar.png" alt="کاربر">
        </div>
        <div class="user-info">
            <div class="user-name"><?php echo $_SESSION['user_full_name'] ?? 'کاربر'; ?></div>
            <div class="user-role">مدیر سیستم</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- داشبورد -->
            <li class="nav-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <a href="?page=dashboard" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>داشبورد</span>
                </a>
            </li>

            <!-- مدیریت مشتریان -->
            <li class="nav-item <?php echo in_array($current_page, ['customers', 'add-customer']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu">
                    <i class="fas fa-users"></i>
                    <span>مدیریت مشتریان</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'customers' ? 'active' : ''; ?>">
                        <a href="?page=customers">لیست مشتریان</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-customer' ? 'active' : ''; ?>">
                        <a href="?page=add-customer">افزودن مشتری</a>
                    </li>
                </ul>
            </li>

            <!-- مدیریت محصولات -->
            <li class="nav-item <?php echo in_array($current_page, ['products', 'add-product', 'categories']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu">
                    <i class="fas fa-box"></i>
                    <span>مدیریت محصولات</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'products' ? 'active' : ''; ?>">
                        <a href="?page=products">لیست محصولات</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-product' ? 'active' : ''; ?>">
                        <a href="?page=add-product">افزودن محصول</a>
                    </li>
                    <li class="<?php echo $current_page === 'categories' ? 'active' : ''; ?>">
                        <a href="?page=categories">دسته‌بندی‌ها</a>
                    </li>
                </ul>
            </li>

            <!-- مدیریت فاکتورها -->
            <li class="nav-item <?php echo in_array($current_page, ['invoices', 'add-invoice']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu">
                    <i class="fas fa-file-invoice"></i>
                    <span>مدیریت فاکتورها</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'invoices' ? 'active' : ''; ?>">
                        <a href="?page=invoices">لیست فاکتورها</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-invoice' ? 'active' : ''; ?>">
                        <a href="?page=add-invoice">صدور فاکتور</a>
                    </li>
                </ul>
            </li>

            <!-- گزارشات -->
            <li class="nav-item <?php echo strpos($current_page, 'report-') === 0 ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu">
                    <i class="fas fa-chart-bar"></i>
                    <span>گزارشات</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'report-sales' ? 'active' : ''; ?>">
                        <a href="?page=report-sales">گزارش فروش</a>
                    </li>
                    <li class="<?php echo $current_page === 'report-customers' ? 'active' : ''; ?>">
                        <a href="?page=report-customers">گزارش مشتریان</a>
                    </li>
                    <li class="<?php echo $current_page === 'report-products' ? 'active' : ''; ?>">
                        <a href="?page=report-products">گزارش محصولات</a>
                    </li>
                </ul>
            </li>

            <!-- تنظیمات -->
            <li class="nav-item <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                <a href="?page=settings" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>تنظیمات</span>
                </a>
            </li>

            <!-- خروج -->
            <li class="nav-item">
                <a href="?page=logout" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>خروج</span>
                </a>
            </li>
        </ul>
    </nav>
</div>