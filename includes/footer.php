<footer class="main-footer">
    <strong>کپی‌رایت &copy; 2024</strong>
    تمامی حقوق محفوظ است.
</footer>

<?php if (isset($page_css) && $page_css === 'categories'): ?>
    <!-- کتابخانه‌های مورد نیاز دسته‌بندی -->
    <link href="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/themes/default/style.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" rel="stylesheet">
    <!-- اسکریپت‌های دسته‌بندی -->
    <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/jstree.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // تنظیم متغیرهای مورد نیاز
        const apiEndpoint = '<?php echo BASE_URL; ?>/includes/handlers/categories_handler.php';
        const baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/assets/js/categories.js"></script>
<?php endif; ?>