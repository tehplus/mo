document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const menuItems = document.querySelectorAll('.nav-link.has-submenu');
    const STORAGE_KEY = 'sidebar_collapsed';
    let touchStartX = 0;

    // بازیابی وضعیت قبلی سایدبار
    if (localStorage.getItem(STORAGE_KEY) === 'true') {
        sidebar.classList.add('collapsed');
    }

    // تابع تغییر وضعیت سایدبار
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem(STORAGE_KEY, sidebar.classList.contains('collapsed'));

        // بستن همه زیرمنوها در حالت جمع شده
        if (sidebar.classList.contains('collapsed')) {
            document.querySelectorAll('.nav-item.active').forEach(item => {
                if (item.querySelector('.has-submenu')) {
                    item.classList.remove('active');
                }
            });
        }
    }

    // رویداد کلیک روی دکمه تغییر وضعیت
    sidebarToggle.addEventListener('click', (e) => {
        e.preventDefault();
        toggleSidebar();
    });

    // مدیریت زیرمنوها
    menuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            
            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                localStorage.setItem(STORAGE_KEY, 'false');
            }

            const parent = item.parentElement;
            const wasActive = parent.classList.contains('active');

            // بستن سایر منوهای باز
            document.querySelectorAll('.nav-item.active').forEach(activeItem => {
                if (activeItem !== parent && activeItem.querySelector('.has-submenu')) {
                    activeItem.classList.remove('active');
                }
            });

            // تغییر وضعیت منوی کلیک شده
            parent.classList.toggle('active', !wasActive);
        });
    });

    // مدیریت کلیک خارج از سایدبار در حالت موبایل
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768) {
            const clickedElement = e.target;
            const isOutsideSidebar = !sidebar.contains(clickedElement);
            const isToggleButton = sidebarToggle.contains(clickedElement);

            if (isOutsideSidebar && !isToggleButton && !sidebar.classList.contains('collapsed')) {
                sidebar.classList.add('collapsed');
                localStorage.setItem(STORAGE_KEY, 'true');
            }
        }
    });

    // مدیریت تغییر سایز پنجره
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                document.querySelector('.sidebar-overlay')?.remove();
            }
        }, 250);
    });

    // مدیریت لمس در موبایل
    sidebar.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    sidebar.addEventListener('touchmove', (e) => {
        if (!touchStartX) return;

        const touchEndX = e.touches[0].clientX;
        const deltaX = touchStartX - touchEndX;

        if (Math.abs(deltaX) > 50) {
            if (deltaX > 0) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
            localStorage.setItem(STORAGE_KEY, sidebar.classList.contains('collapsed'));
            touchStartX = null;
        }
    });

    sidebar.addEventListener('touchend', () => {
        touchStartX = null;
    });

    // اضافه کردن کلاس active به منوی صفحه جاری
    const currentPage = new URLSearchParams(window.location.search).get('page') || 'dashboard';
    const currentMenuItem = document.querySelector(`.nav-item a[href*="${currentPage}"]`);
    
    if (currentMenuItem) {
        const parentItem = currentMenuItem.closest('.nav-item');
        const parentWithSubmenu = currentMenuItem.closest('.nav-item:has(.submenu)');
        
        if (parentItem) {
            parentItem.classList.add('active');
        }
        
        if (parentWithSubmenu) {
            parentWithSubmenu.classList.add('active');
        }
    }
});

// تابع خروج از سیستم با تأیید
function confirmLogout() {
    if (confirm('آیا مطمئن هستید که می‌خواهید از سیستم خارج شوید؟')) {
        window.location.href = '?page=logout';
    }
}