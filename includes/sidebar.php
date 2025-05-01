<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="assets/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">حسابدار هوشمند</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/img/user.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['username'] ?? 'کاربر مهمان'; ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>داشبورد</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=transactions" class="nav-link <?php echo $page == 'transactions' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>تراکنش‌ها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=invoices" class="nav-link <?php echo $page == 'invoices' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>فاکتورها</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=reports" class="nav-link <?php echo $page == 'reports' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>گزارشات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?php echo $page == 'settings' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>تنظیمات</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>