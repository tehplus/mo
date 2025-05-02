<?php
/**
 * سایدبار اصلی برنامه
 * این فایل شامل منوی اصلی برنامه و پروفایل کاربر است
 * 
 * @author tehplus
 * @version 1.0.1
 * @since 2025-05-02 06:01:40
 */

// دریافت صفحه فعلی
$current_page = $_GET['page'] ?? 'dashboard';

// دریافت اطلاعات کاربر
$user_name = $_SESSION['user_full_name'] ?? 'کاربر گرامی';
$user_role = $_SESSION['user_role'] ?? 'مدیر سیستم';
$user_avatar = $_SESSION['user_avatar'] ?? BASE_URL . '/assets/images/avatar.png';
?>

<div class="sidebar" id="sidebar">
    <!-- هدر سایدبار -->
    <div class="sidebar-header">
        <div class="logo">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="<?php echo APP_NAME; ?>">
            <span class="logo-text"><?php echo APP_NAME; ?></span>
        </div>
        <button id="sidebar-toggle" class="sidebar-toggle" title="تغییر حالت منو">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- پروفایل کاربر -->
    <div class="sidebar-user">
        <div class="user-image">
            <img src="<?php echo $user_avatar; ?>" alt="<?php echo htmlspecialchars($user_name); ?>">
        </div>
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($user_name); ?></div>
            <div class="user-role"><?php echo htmlspecialchars($user_role); ?></div>
        </div>
    </div>

    <!-- منوی اصلی -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <!-- داشبورد -->
            <li class="nav-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                <a href="?page=dashboard" class="nav-link" data-title="داشبورد">
                    <i class="fas fa-home"></i>
                    <span>داشبورد</span>
                </a>
            </li>

            <!-- مدیریت مشتریان -->
            <li class="nav-item <?php echo in_array($current_page, ['customers', 'add-customer']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu" data-title="مدیریت مشتریان">
                    <i class="fas fa-users"></i>
                    <span>مدیریت مشتریان</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'customers' ? 'active' : ''; ?>">
                        <a href="?page=customers" data-title="لیست مشتریان">لیست مشتریان</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-customer' ? 'active' : ''; ?>">
                        <a href="?page=add-customer" data-title="افزودن مشتری">افزودن مشتری</a>
                    </li>
                </ul>
            </li>

            <!-- مدیریت محصولات -->
            <li class="nav-item <?php echo in_array($current_page, ['products', 'add-product', 'categories']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu" data-title="مدیریت محصولات">
                    <i class="fas fa-box"></i>
                    <span>مدیریت محصولات</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'products' ? 'active' : ''; ?>">
                        <a href="?page=products" data-title="لیست محصولات">لیست محصولات</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-product' ? 'active' : ''; ?>">
                        <a href="?page=add-product" data-title="افزودن محصول">افزودن محصول</a>
                    </li>
                    <li class="<?php echo $current_page === 'categories' ? 'active' : ''; ?>">
                        <a href="?page=categories" data-title="دسته‌بندی‌ها">دسته‌بندی‌ها</a>
                    </li>
                </ul>
            </li>

            <!-- مدیریت فاکتورها -->
            <li class="nav-item <?php echo in_array($current_page, ['invoices', 'add-invoice']) ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu" data-title="مدیریت فاکتورها">
                    <i class="fas fa-file-invoice"></i>
                    <span>مدیریت فاکتورها</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'invoices' ? 'active' : ''; ?>">
                        <a href="?page=invoices" data-title="لیست فاکتورها">لیست فاکتورها</a>
                    </li>
                    <li class="<?php echo $current_page === 'add-invoice' ? 'active' : ''; ?>">
                        <a href="?page=add-invoice" data-title="صدور فاکتور">صدور فاکتور</a>
                    </li>
                </ul>
            </li>

            <!-- گزارشات -->
            <li class="nav-item <?php echo strpos($current_page, 'report-') === 0 ? 'active' : ''; ?>">
                <a href="#" class="nav-link has-submenu" data-title="گزارشات">
                    <i class="fas fa-chart-bar"></i>
                    <span>گزارشات</span>
                    <i class="fas fa-chevron-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li class="<?php echo $current_page === 'report-sales' ? 'active' : ''; ?>">
                        <a href="?page=report-sales" data-title="گزارش فروش">گزارش فروش</a>
                    </li>
                    <li class="<?php echo $current_page === 'report-customers' ? 'active' : ''; ?>">
                        <a href="?page=report-customers" data-title="گزارش مشتریان">گزارش مشتریان</a>
                    </li>
                    <li class="<?php echo $current_page === 'report-products' ? 'active' : ''; ?>">
                        <a href="?page=report-products" data-title="گزارش محصولات">گزارش محصولات</a>
                    </li>
                </ul>
            </li>

            <!-- تنظیمات -->
            <li class="nav-item <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
                <a href="?page=settings" class="nav-link" data-title="تنظیمات">
                    <i class="fas fa-cog"></i>
                    <span>تنظیمات</span>
                </a>
            </li>

            <!-- خروج -->
            <li class="nav-item">
                <a href="#" class="nav-link" data-title="خروج" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>خروج</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- سایه برای حالت موبایل -->
    <div class="sidebar-overlay"></div>
</div>

<!-- اسکریپت جاوااسکریپت سایدبار -->
<script src="<?php echo BASE_URL; ?>/assets/js/sidebar.js"></script>