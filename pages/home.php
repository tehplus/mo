<?php
/**
 * صفحه اصلی (لندینگ)
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// تنظیم عنوان صفحه
$meta['title'] = APP_NAME . ' - صفحه اصلی';
$meta['description'] = 'سیستم حسابداری آنلاین برای مدیریت هوشمند مالی کسب و کار شما';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $meta['title']; ?></title>
    <meta name="description" content="<?php echo $meta['description']; ?>">
    
    <!-- استایل‌ها -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- هدر -->
    <header class="header">
        <nav class="nav-container">
            <a href="<?php echo BASE_URL; ?>" class="logo">
                <?php echo APP_NAME; ?>
            </a>
            <div class="nav-menu">
                <a href="#features" class="nav-link">ویژگی‌ها</a>
                <a href="#pricing" class="nav-link">تعرفه‌ها</a>
                <a href="#testimonials" class="nav-link">نظرات کاربران</a>
                <a href="#contact" class="nav-link">تماس با ما</a>
                <a href="?page=login" class="nav-link btn btn-secondary">ورود</a>
                <a href="?page=register" class="nav-link btn btn-primary">ثبت‌نام</a>
            </div>
        </nav>
    </header>

    <!-- بخش قهرمان -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">مدیریت هوشمند مالی کسب و کار شما</h1>
            <p class="hero-subtitle">با استفاده از سیستم حسابداری ما، مدیریت مالی کسب و کار خود را آسان کنید</p>
            <div class="hero-buttons">
                <a href="?page=register" class="btn btn-primary">شروع رایگان</a>
                <a href="#features" class="btn btn-secondary">بیشتر بدانید</a>
            </div>
        </div>
    </section>

    <!-- بخش ویژگی‌ها -->
    <section id="features" class="features">
        <div class="section-header">
            <h2>ویژگی‌های برتر</h2>
            <p>امکانات پیشرفته برای مدیریت بهتر کسب و کار شما</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>گزارش‌گیری پیشرفته</h3>
                <p>تحلیل داده‌های مالی با نمودارهای تعاملی و گزارش‌های سفارشی</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-receipt"></i>
                <h3>صدور فاکتور</h3>
                <p>ایجاد و مدیریت فاکتورهای حرفه‌ای با قالب‌های متنوع</p>
            </div>
            <!-- ادامه ویژگی‌ها -->
        </div>
    </section>

    <!-- فوتر -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>درباره ما</h3>
                <p><?php echo APP_NAME; ?>، راهکاری جامع برای مدیریت مالی کسب و کارها</p>
            </div>
            <div class="footer-section">
                <h3>لینک‌های مفید</h3>
                <ul>
                    <li><a href="#features">ویژگی‌ها</a></li>
                    <li><a href="#pricing">تعرفه‌ها</a></li>
                    <li><a href="#testimonials">نظرات</a></li>
                    <li><a href="#contact">تماس</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> تمامی حقوق محفوظ است.</p>
        </div>
    </footer>

    <!-- اسکریپت‌ها -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/landing.js"></script>
</body>
</html>