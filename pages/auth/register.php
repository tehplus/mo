<?php
session_start();

// اگر کاربر قبلاً لاگین کرده است
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// پردازش فرم ثبت نام
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $response = ['success' => false, 'message' => ''];
    
    try {
        // دریافت و پاکسازی داده‌ها
        $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        
        // اعتبارسنجی داده‌ها
        if (empty($fullName) || empty($username) || empty($email) || empty($password)) {
            throw new Exception('لطفاً تمام فیلدها را پر کنید.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('لطفاً یک ایمیل معتبر وارد کنید.');
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception('رمز عبور و تکرار آن مطابقت ندارند.');
        }
        
        if (strlen($password) < 8) {
            throw new Exception('رمز عبور باید حداقل 8 کاراکتر باشد.');
        }
        
        // بررسی تکراری نبودن نام کاربری و ایمیل
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            throw new Exception('این نام کاربری یا ایمیل قبلاً ثبت شده است.');
        }
        
        // ذخیره کاربر جدید
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (full_name, username, email, password, created_at) VALUES (?, ?, ?, ?, NOW())");
        if ($stmt->execute([$fullName, $username, $email, $hashedPassword])) {
            $response['success'] = true;
            $response['message'] = 'ثبت نام با موفقیت انجام شد.';
        } else {
            throw new Exception('خطا در ثبت اطلاعات.');
        }
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام | سیستم حسابداری</title>
    
    <!-- فونت‌آوسام -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- بوت‌استرپ -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- استایل‌های سفارشی -->
    <link rel="stylesheet" href="assets/css/auth.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="assets/img/logo.png" alt="Logo" class="auth-logo">
                <h1 class="auth-title">ثبت نام در سیستم</h1>
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
                
                <!-- قوانین و مقررات -->
                <label class="custom-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span class="checkmark"></span>
                    <span class="text">با <a href="#" target="_blank">قوانین و مقررات</a> موافقم</span>
                </label>
                
                <!-- دکمه ثبت نام -->
                <button type="submit" class="btn btn-primary">
                    <span class="loading-spinner"></span>
                    <span class="btn-text">ثبت نام</span>
                </button>
            </form>
            
            <div class="auth-footer">
                <p>قبلاً ثبت نام کرده‌اید؟ <a href="?page=login">ورود به سیستم</a></p>
            </div>
        </div>
    </div>

    <!-- اسکریپت‌ها -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>