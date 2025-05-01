<?php
// تنظیم header برای حل مشکل کاراکترهای فارسی
header('Content-Type: application/json; charset=utf-8');

// بررسی نوع درخواست
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false, 
        'message' => 'درخواست نامعتبر است'
    ]);
    exit;
}

try {
    // لود کردن فایل‌های مورد نیاز
    require_once __DIR__ . '/../../includes/config.php';
    require_once __DIR__ . '/../../includes/classes/Database.php';
    
    // ایجاد اتصال به دیتابیس
    $db = new Database();
    $conn = $db->getConnection();
    
    // دریافت و پاکسازی داده‌ها
    $fullName = trim(filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING));
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // اعتبارسنجی داده‌ها
    $errors = [];
    
    if (empty($fullName)) {
        $errors['fullName'] = 'نام و نام خانوادگی الزامی است';
    }
    
    if (empty($username)) {
        $errors['username'] = 'نام کاربری الزامی است';
    } elseif (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{3,19}$/', $username)) {
        $errors['username'] = 'نام کاربری باید با حرف شروع شده و فقط شامل حروف، اعداد و _ باشد';
    }
    
    if (empty($email)) {
        $errors['email'] = 'ایمیل الزامی است';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'ایمیل معتبر نیست';
    }
    
    if (empty($password)) {
        $errors['password'] = 'رمز عبور الزامی است';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'رمز عبور باید حداقل 8 کاراکتر باشد';
    }
    
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'رمز عبور و تکرار آن مطابقت ندارند';
    }
    
    // اگر خطایی نبود
    if (empty($errors)) {
        // بررسی تکراری نبودن نام کاربری و ایمیل
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            $errors['username'] = 'این نام کاربری یا ایمیل قبلاً ثبت شده است';
        } else {
            // هش کردن رمز عبور
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // درج کاربر جدید
            $stmt = $conn->prepare(
                "INSERT INTO users (full_name, username, email, password, created_at) 
                 VALUES (?, ?, ?, ?, NOW())"
            );
            
            $success = $stmt->execute([$fullName, $username, $email, $hashedPassword]);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ثبت نام با موفقیت انجام شد'
                ]);
                exit;
            } else {
                throw new Exception("خطا در ذخیره اطلاعات");
            }
        }
    }
    
    // اگر خطایی وجود داشت
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
} catch (PDOException $e) {
    // لاگ کردن خطا
    error_log("Database Error in register_handler.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'خطا در ارتباط با پایگاه داده'
    ]);
    exit;
    
} catch (Exception $e) {
    // لاگ کردن خطا
    error_log("General Error in register_handler.php: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}