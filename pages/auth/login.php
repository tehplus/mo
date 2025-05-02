<?php
/**
 * صفحه ورود به سیستم
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02
 */

// اگر کاربر قبلاً لاگین کرده، به داشبورد هدایت شود
if (isset($_SESSION['user_id'])) {
    header('Location: ?page=dashboard');
    exit;
}

// عنوان صفحه
$meta['title'] = 'ورود به سیستم - ' . APP_NAME;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="صفحه ورود به سیستم حسابداری هوشمند">
    <title><?php echo $meta['title']; ?></title>
    
    <!-- فونت‌ها -->
    <link rel="stylesheet" href="assets/fonts/anjoman/stylesheet.css">
    
    <!-- استایل‌های CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- استایل‌های اختصاصی -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="login-page">
    <div class="auth-wrapper">
        <div class="auth-header text-center mb-4">
            <img src="assets/img/logo-light.png" alt="<?php echo APP_NAME; ?>" class="img-fluid mb-3" width="120">
            <h1 class="h3 fw-normal text-white"><?php echo APP_NAME; ?></h1>
        </div>

        <div class="auth-box">
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">ورود به حساب کاربری</h2>
                    
                    <form id="loginForm" method="post" class="needs-validation" novalidate autocomplete="off">
                        <!-- نام کاربری -->
                        <div class="form-floating mb-3">
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="نام کاربری یا ایمیل"
                                   required 
                                   autofocus>
                            <label for="username">
                                <i class="fas fa-user me-2"></i>
                                نام کاربری یا ایمیل
                            </label>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- رمز عبور -->
                        <div class="form-floating mb-3">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="رمز عبور"
                                   required>
                            <label for="password">
                                <i class="fas fa-lock me-2"></i>
                                رمز عبور
                            </label>
                            <button type="button" class="btn btn-link password-toggle" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- گزینه‌های اضافی -->
                        <div class="row mb-4">
                            <div class="col-7">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        مرا به خاطر بسپار
                                    </label>
                                </div>
                            </div>
                            <div class="col-5 text-start">
                                <a href="?page=forgot-password" class="text-decoration-none">
                                    فراموشی رمز؟
                                </a>
                            </div>
                        </div>

                        <!-- دکمه ورود -->
                        <div class="d-grid mb-4">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                ورود به سیستم
                            </button>
                        </div>

                        <!-- لینک ثبت نام -->
                        <div class="text-center">
                            <span>حساب کاربری ندارید؟</span>
                            <a href="?page=register" class="text-decoration-none">ثبت‌نام کنید</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- فوتر -->
            <div class="auth-footer text-center mt-4">
                <p class="text-white-50 mb-0">
                    <?php echo APP_NAME . ' &copy; ' . date('Y'); ?>
                </p>
            </div>
        </div>
    </div>

    <!-- اسکریپت‌های CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
    <!-- اسکریپت‌های اختصاصی -->
    <script src="assets/js/login.js"></script>
</body>
</html>