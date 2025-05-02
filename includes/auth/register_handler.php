<?php
// اگر درخواست Ajax نیست
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('HTTP/1.1 403 Forbidden');
    die('دسترسی مستقیم به این فایل مجاز نیست');
}

// تنظیم header برای JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // دریافت و پاکسازی داده‌ها با روش جدید
    $fullName = htmlspecialchars(trim($_POST['fullName'] ?? ''), ENT_QUOTES, 'UTF-8');
    $username = htmlspecialchars(trim($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
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
        $db = new Database();
        $conn = $db->getConnection();
        
        // بررسی تکراری نبودن نام کاربری و ایمیل
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'success' => false,
                'errors' => ['username' => 'این نام کاربری یا ایمیل قبلاً ثبت شده است']
            ]);
            exit;
        }
        
        // هش کردن رمز عبور
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // درج کاربر جدید
        $stmt = $conn->prepare(
            "INSERT INTO users (full_name, username, email, password, created_at) 
             VALUES (?, ?, ?, ?, NOW())"
        );
        
        if ($stmt->execute([$fullName, $username, $email, $hashedPassword])) {
            echo json_encode([
                'success' => true,
                'message' => 'ثبت نام با موفقیت انجام شد'
            ]);
        } else {
            throw new Exception("خطا در ذخیره اطلاعات");
        }
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
    
} catch (PDOException $e) {
    error_log("Database Error in register_handler.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'خطا در ارتباط با پایگاه داده'
    ]);
} catch (Exception $e) {
    error_log("General Error in register_handler.php: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}