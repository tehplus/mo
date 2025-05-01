<?php
/**
 * صفحه ثبت نام
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-01
 */

// تنظیم عنوان صفحه
$meta['title'] = APP_NAME . ' - ثبت نام';

// اگر درخواست Ajax است
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    require_once BASE_PATH . '/includes/auth/register_handler.php';
    exit;
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $meta['title']; ?></title>
    
    <!-- استایل‌ها -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/auth.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="<?php echo BASE_URL; ?>" class="auth-logo">
                    <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" alt="Logo">
                </a>
                <h1 class="auth-title">ثبت نام در <?php echo APP_NAME; ?></h1>
                <p class="auth-subtitle">برای استفاده از امکانات سیستم، لطفاً ثبت نام کنید</p>
            </div>
            
            <form id="registerForm" class="auth-form" novalidate>
                <!-- نام و نام خانوادگی -->
                <div class="form-group">
                    <label for="fullName">نام و نام خانوادگی</label>
                    <input type="text" id="fullName" name="fullName" class="form-control" 
                           required autocomplete="name">
                    <div class="error-feedback"></div>
                </div>
                
                <!-- نام کاربری -->
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           required autocomplete="username">
                    <div class="error-feedback"></div>
                </div>
                
                <!-- ایمیل -->
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input type="email" id="email" name="email" class="form-control" 
                           required autocomplete="email">
                    <div class="error-feedback"></div>
                </div>
                
                <!-- رمز عبور -->
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-control" 
                               required autocomplete="new-password">
                        <span class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="error-feedback"></div>
                </div>
                
                <!-- تکرار رمز عبور -->
                <div class="form-group">
                    <label for="confirmPassword">تکرار رمز عبور</label>
                    <div class="password-field">
                        <input type="password" id="confirmPassword" name="confirmPassword" 
                               class="form-control" required autocomplete="new-password">
                        <span class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="error-feedback"></div>
                </div>
                
                <div class="mb-3">
    <div class="form-check">
        <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
        <label class="form-check-label" for="terms">
            با <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">قوانین و مقررات</a> موافقم
        </label>
    </div>
</div>
                
                <!-- دکمه ثبت نام -->
                <button type="submit" class="btn btn-primary w-100">
                    <span class="loading-spinner"></span>
                    <span class="btn-text">ثبت نام</span>
                </button>
            </form>
            
            <div class="auth-footer">
                <p>قبلاً ثبت نام کرده‌اید؟ <a href="?page=login">ورود به سیستم</a></p>
            </div>
        </div>
    </div>

    <!-- Modal قوانین -->
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">قوانین و مقررات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- محتوای قوانین -->
                    <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم...</p>
                </div>
            </div>
        </div>
    </div>
<style>
.form-check {
    padding-right: 1.5rem;
    padding-left: 0;
}

.form-check .form-check-input {
    float: right;
    margin-right: -1.5rem;
    margin-left: 0;
}

.form-check-input {
    width: 1rem;
    height: 1rem;
    margin-top: 0.25rem;
    vertical-align: top;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    border: 1px solid rgba(0, 0, 0, 0.25);
    appearance: none;
    print-color-adjust: exact;
    cursor: pointer;
}

.form-check-input:checked {
    background-color: #2563eb;
    border-color: #2563eb;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
}
</style>
    <!-- اسکریپت‌ها -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/auth.js"></script>

    <script>
if (!document.getElementById('terms').checked) {
    Swal.fire({
        title: 'خطا!',
        text: 'لطفاً قوانین و مقررات را مطالعه و قبول کنید.',
        icon: 'error',
        confirmButtonText: 'باشه'
    });
    return false;
}

    </script>
</body>
</html>