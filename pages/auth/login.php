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
    <title><?php echo $meta['title']; ?></title>
    
    <!-- فونت‌های ایران‌سنس -->
    <link href="assets/fonts/anjoman/stylesheet.css" rel="stylesheet">
    
    <!-- استایل‌های اصلی -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">

    <link rel="stylesheet" href="assets/css/login.css">
    
    <!-- فونت‌آیکون‌ها -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
</head>
<body class="login-page">
    
    <div class="login-wrapper">
        <div class="login-box">
            <!-- لوگو -->
            <div class="login-logo mb-4">
                <img src="/mo/assets/img/logo.png" alt="<?php echo APP_NAME; ?>" class="img-fluid">
                <h1 class="mt-3"><?php echo APP_NAME; ?></h1>
            </div>
            
            <!-- فرم ورود -->
            <div class="card">
                <div class="card-body">
                    <form id="loginForm" method="post" class="needs-validation" novalidate>
                        <h5 class="card-title text-center mb-4">ورود به حساب کاربری</h5>
                        
                        <!-- نام کاربری یا ایمیل -->
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">نام کاربری یا ایمیل</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       required 
                                       autofocus
                                       placeholder="نام کاربری یا ایمیل خود را وارد کنید">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        
                        <!-- رمز عبور -->
                        <div class="form-group mb-4">
                            <label for="password" class="form-label">رمز عبور</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="رمز عبور خود را وارد کنید">
                                <button type="button" class="input-group-text password-toggle" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="invalid-feedback"></div>
                            </div>
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
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                ورود به سیستم
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- لینک ثبت‌نام -->
            <div class="text-center mt-4">
                <p class="mb-0">
                    هنوز ثبت‌نام نکرده‌اید؟ 
                    <a href="?page=register" class="text-decoration-none">
                        همین حالا ثبت‌نام کنید
                    </a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- اسکریپت‌های ضروری -->
    <script src="/mo/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/mo/assets/js/auth.js"></script>
</body>
</html>