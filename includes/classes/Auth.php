<?php

class Auth {
    private $db;
    private $errors = [];
    
    public function __construct() {
        require_once BASE_PATH . '/includes/classes/Database.php';
        $this->db = (new Database())->getConnection();
    }
    
    /**
     * اعتبارسنجی داده‌های ثبت نام
     */
    private function validateRegistration($data) {
        $this->errors = [];
        
        // نام و نام خانوادگی
        if (empty($data['fullName'])) {
            $this->errors['fullName'] = 'نام و نام خانوادگی الزامی است';
        } elseif (strlen($data['fullName']) < 3) {
            $this->errors['fullName'] = 'نام و نام خانوادگی باید حداقل 3 کاراکتر باشد';
        }
        
        // نام کاربری
        if (empty($data['username'])) {
            $this->errors['username'] = 'نام کاربری الزامی است';
        } elseif (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $data['username'])) {
            $this->errors['username'] = 'نام کاربری باید بین 4 تا 20 کاراکتر و فقط شامل حروف، اعداد و _ باشد';
        }
        
        // ایمیل
        if (empty($data['email'])) {
            $this->errors['email'] = 'ایمیل الزامی است';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'لطفاً یک ایمیل معتبر وارد کنید';
        }
        
        // رمز عبور
        if (empty($data['password'])) {
            $this->errors['password'] = 'رمز عبور الزامی است';
        } elseif (strlen($data['password']) < 8) {
            $this->errors['password'] = 'رمز عبور باید حداقل 8 کاراکتر باشد';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/', $data['password'])) {
            $this->errors['password'] = 'رمز عبور باید شامل حروف بزرگ، کوچک، اعداد و کاراکترهای خاص باشد';
        }
        
        // تکرار رمز عبور
        if ($data['password'] !== $data['confirmPassword']) {
            $this->errors['confirmPassword'] = 'تکرار رمز عبور مطابقت ندارد';
        }
        
        // بررسی تکراری نبودن نام کاربری و ایمیل
        $stmt = $this->db->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$data['username'], $data['email']]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingUser) {
            if ($existingUser['username'] === $data['username']) {
                $this->errors['username'] = 'این نام کاربری قبلاً ثبت شده است';
            }
            if ($existingUser['email'] === $data['email']) {
                $this->errors['email'] = 'این ایمیل قبلاً ثبت شده است';
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * ثبت نام کاربر جدید
     */
    public function register($data) {
        try {
            if (!$this->validateRegistration($data)) {
                return [
                    'success' => false,
                    'errors' => $this->errors
                ];
            }
            
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("
                INSERT INTO users (full_name, username, email, password, created_at) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            $success = $stmt->execute([
                $data['fullName'],
                $data['username'],
                $data['email'],
                $hashedPassword
            ]);
            
            if ($success) {
                return [
                    'success' => true,
                    'message' => 'ثبت نام با موفقیت انجام شد'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'خطا در ثبت اطلاعات'
                ];
            }
            
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با پایگاه داده'
            ];
        } catch (Exception $e) {
            error_log("General Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'خطای سیستمی'
            ];
        }
    }
    
    /**
     * بررسی معتبر بودن درخواست AJAX
     */
    public static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}