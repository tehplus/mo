<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        require_once 'includes/classes/Database.php';
        $db = (new Database())->getConnection();
        
        // دریافت و پاکسازی داده‌ها
        $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $response = ['success' => false, 'errors' => []];
        
        // اعتبارسنجی داده‌ها
        if (empty($fullName)) {
            $response['errors']['fullName'] = 'نام و نام خانوادگی الزامی است';
        }
        
        if (empty($username)) {
            $response['errors']['username'] = 'نام کاربری الزامی است';
        }
        
        if (empty($email)) {
            $response['errors']['email'] = 'ایمیل الزامی است';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['errors']['email'] = 'ایمیل معتبر نیست';
        }
        
        if (empty($password)) {
            $response['errors']['password'] = 'رمز عبور الزامی است';
        }
        
        if ($password !== $confirmPassword) {
            $response['errors']['confirmPassword'] = 'رمز عبور و تکرار آن مطابقت ندارند';
        }
        
        // اگر خطایی نبود
        if (empty($response['errors'])) {
            // بررسی تکراری نبودن نام کاربری و ایمیل
            $stmt = $db->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $response['errors']['username'] = 'این نام کاربری یا ایمیل قبلاً ثبت شده است';
            } else {
                // ایجاد کاربر جدید
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $db->prepare(
                    "INSERT INTO users (full_name, username, email, password, created_at) 
                     VALUES (?, ?, ?, ?, NOW())"
                );
                
                if ($stmt->execute([$fullName, $username, $email, $hashedPassword])) {
                    $response['success'] = true;
                    $response['message'] = 'ثبت نام با موفقیت انجام شد';
                } else {
                    $response['errors']['general'] = 'خطا در ثبت اطلاعات';
                }
            }
        }
        
        echo json_encode($response);
        exit;
        
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'errors' => ['general' => 'خطا در ارتباط با پایگاه داده']
        ]);
        exit;
    }
}

// اگر درخواست POST نباشد
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);