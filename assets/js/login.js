/**
 * اسکریپت‌های اختصاصی صفحه لاگین
 * @version 1.0.0
 * @since 2025-05-02
 */

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    /**
     * نمایش خطا در فیلد ورودی
     * @param {HTMLElement} input - فیلد ورودی
     * @param {string} message - پیام خطا
     */
    function showError(input, message) {
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    }

    /**
     * پاک کردن خطا از فیلد ورودی
     * @param {HTMLElement} input - فیلد ورودی
     */
    function clearError(input) {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    }

    /**
     * مدیریت نمایش/مخفی کردن رمز عبور
     */
    const passwordToggle = loginForm.querySelector('.password-toggle');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const input = this.previousElementSibling.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    /**
     * مدیریت ارسال فرم
     */
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // پاک کردن خطاهای قبلی
        this.querySelectorAll('.form-control').forEach(input => clearError(input));
        
        // اعتبارسنجی اولیه
        const username = this.querySelector('#username');
        const password = this.querySelector('#password');
        let hasError = false;

        if (!username.value.trim()) {
            showError(username, 'لطفاً نام کاربری یا ایمیل خود را وارد کنید');
            hasError = true;
        }

        if (!password.value) {
            showError(password, 'لطفاً رمز عبور خود را وارد کنید');
            hasError = true;
        }

        if (hasError) return;

        // نمایش لودینگ
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // ارسال درخواست به سرور
        const formData = new FormData(this);
        
        fetch('?page=login', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('خطا در ارتباط با سرور');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // ریدایرکت به داشبورد
                window.location.href = '?page=dashboard';
            } else if (data.errors) {
                // نمایش خطاها
                Object.entries(data.errors).forEach(([field, message]) => {
                    const input = this.querySelector(`#${field}`);
                    if (input) showError(input, message);
                });
            } else {
                throw new Error(data.message || 'خطا در ورود به سیستم');
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
    });

    // پاک کردن خطا هنگام تایپ
    loginForm.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', () => clearError(input));
    });
});