document.addEventListener('DOMContentLoaded', function() {
    // فرم ثبت نام
    const registerForm = document.getElementById('registerForm');
    const passwordFields = document.querySelectorAll('.password-field input[type="password"]');
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    // تابع اعتبارسنجی ایمیل
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // تابع اعتبارسنجی رمز عبور
    function isValidPassword(password) {
        // حداقل 8 کاراکتر، شامل حروف بزرگ و کوچک و اعداد
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
        return passwordRegex.test(password);
    }
    
    // تابع نمایش خطا
    function showError(input, message) {
        const formGroup = input.closest('.form-group');
        input.classList.add('is-invalid');
        const error = formGroup.querySelector('.error-feedback');
        if (error) {
            error.textContent = message;
            error.style.display = 'block';
        }
    }
    
    // تابع پاک کردن خطا
    function clearError(input) {
        const formGroup = input.closest('.form-group');
        input.classList.remove('is-invalid');
        const error = formGroup.querySelector('.error-feedback');
        if (error) {
            error.style.display = 'none';
        }
    }
    
    // مدیریت نمایش/مخفی کردن رمز عبور
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type');
            const newType = type === 'password' ? 'text' : 'password';
            input.setAttribute('type', newType);
            
            // تغییر آیکون
            const icon = this.querySelector('i');
            if (newType === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // اعتبارسنجی فرم هنگام ارسال
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            let hasError = false;
            
            // پاک کردن خطاهای قبلی
            registerForm.querySelectorAll('.form-control').forEach(input => {
                clearError(input);
            });
            
            // اعتبارسنجی نام و نام خانوادگی
            const fullName = registerForm.querySelector('#fullName');
            if (!fullName.value.trim()) {
                showError(fullName, 'لطفاً نام و نام خانوادگی خود را وارد کنید');
                hasError = true;
            }
            
            // اعتبارسنجی نام کاربری
            const username = registerForm.querySelector('#username');
            if (username.value.length < 4) {
                showError(username, 'نام کاربری باید حداقل 4 کاراکتر باشد');
                hasError = true;
            }
            
            // اعتبارسنجی ایمیل
            const email = registerForm.querySelector('#email');
            if (!isValidEmail(email.value)) {
                showError(email, 'لطفاً یک ایمیل معتبر وارد کنید');
                hasError = true;
            }
            
            // اعتبارسنجی رمز عبور
            const password = registerForm.querySelector('#password');
            const confirmPassword = registerForm.querySelector('#confirmPassword');
            
            if (!isValidPassword(password.value)) {
                showError(password, 'رمز عبور باید حداقل 8 کاراکتر و شامل حروف بزرگ، کوچک و اعداد باشد');
                hasError = true;
            }
            
            if (password.value !== confirmPassword.value) {
                showError(confirmPassword, 'تکرار رمز عبور مطابقت ندارد');
                hasError = true;
            }
            
            // بررسی پذیرش قوانین
            const terms = registerForm.querySelector('#terms');
            if (!terms.checked) {
                Swal.fire({
                    title: 'خطا!',
                    text: 'لطفاً قوانین و مقررات را مطالعه و قبول کنید',
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
                hasError = true;
            }
            
            if (!hasError) {
                // نمایش loading در دکمه
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                // ارسال اطلاعات به سرور
                const formData = new FormData(registerForm);
                
                fetch('register.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'موفق!',
                            text: 'ثبت نام شما با موفقیت انجام شد',
                            icon: 'success',
                            confirmButtonText: 'باشه'
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    } else {
                        throw new Error(data.message || 'خطا در ثبت نام');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'خطا!',
                        text: error.message,
                        icon: 'error',
                        confirmButtonText: 'باشه'
                    });
                })
                .finally(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                });
            }
        });
        
        // اعتبارسنجی آنی فیلدها
        registerForm.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                clearError(this);
            });
        });
    }
});