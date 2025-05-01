<?php
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

<!-- محتوای صفحه ثبت نام -->
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>ثبت نام در سیستم</h1>
                <p>برای استفاده از امکانات سیستم، لطفاً ثبت نام کنید</p>
            </div>
            
            <form id="registerForm" class="auth-form">
                <div class="form-group">
                    <label for="fullName">نام و نام خانوادگی</label>
                    <input type="text" id="fullName" name="fullName" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <i class="fas fa-eye password-toggle"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirmPassword">تکرار رمز عبور</label>
                    <div class="password-field">
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" required>
                        <i class="fas fa-eye password-toggle"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="terms" required>
                        <label class="custom-control-label" for="terms">
                            با <a href="#" target="_blank">قوانین و مقررات</a> موافقم
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">ثبت نام</button>
            </form>
            
            <div class="auth-footer">
                <p>قبلاً ثبت نام کرده‌اید؟ <a href="?page=login">ورود به سیستم</a></p>
            </div>
        </div>
    </div>
</div>

<!-- اضافه کردن CSS اختصاصی -->
<style>
.auth-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #2563eb, #1e40af);
    padding: 20px;
}

.auth-container {
    width: 100%;
    max-width: 500px;
}

.auth-card {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.auth-header {
    text-align: center;
    margin-bottom: 30px;
}

.auth-header h1 {
    font-size: 24px;
    margin-bottom: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6b7280;
}

.auth-footer {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e5e7eb;
}

.auth-footer a {
    color: #2563eb;
    text-decoration: none;
}

.auth-footer a:hover {
    text-decoration: underline;
}
</style>

<!-- اضافه کردن JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // مدیریت نمایش/مخفی کردن رمز عبور
    document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const type = input.getAttribute('type');
            
            if (type === 'password') {
                input.setAttribute('type', 'text');
                this.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.setAttribute('type', 'password');
                this.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    });

    // مدیریت ارسال فرم
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('?page=register', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'موفق!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'باشه'
                }).then(() => {
                    window.location.href = '?page=login';
                });
            } else {
                Swal.fire({
                    title: 'خطا!',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'خطا!',
                text: 'خطا در ارتباط با سرور',
                icon: 'error',
                confirmButtonText: 'باشه'
            });
        });
    });
});
</script>