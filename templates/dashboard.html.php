<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <?php require_once 'includes/head.php'; ?>
    <title>داشبورد - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <?php require_once 'includes/sidebar.php'; ?>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="welcome-section">
                <h1>خوش آمدید، <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'کاربر گرامی'); ?></h1>
                <div class="date-time">
                    <?php echo jdate('l j F Y'); ?> | <span id="live-clock"></span>
                </div>
            </div>
            <div class="header-actions">
                <button onclick="refreshDashboard()" class="refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                    بروزرسانی
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <!-- Sales Card -->
            <div class="stat-card">
                <div class="stat-icon sales">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo number_format($todaySales); ?></h3>
                    <p>فروش امروز (تومان)</p>
                </div>
            </div>

            <!-- Customers Card -->
            <div class="stat-card">
                <div class="stat-icon customers">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo number_format($totalCustomers); ?></h3>
                    <p>مشتریان فعال</p>
                </div>
            </div>

            <!-- Products Card -->
            <div class="stat-card">
                <div class="stat-icon products">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo number_format($totalProducts); ?></h3>
                    <p>محصولات موجود</p>
                </div>
            </div>

            <!-- Income Card -->
            <div class="stat-card">
                <div class="stat-icon income">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo number_format($monthlyIncome); ?></h3>
                    <p>درآمد این ماه (تومان)</p>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-grid">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">نمودار فروش</h3>
                    <div class="chart-actions">
                        <select class="chart-period-select" data-chart="sales">
                            <option value="week">هفتگی</option>
                            <option value="month" selected>ماهانه</option>
                            <option value="year">سالانه</option>
                        </select>
                    </div>
                </div>
                <div id="salesChart"></div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">مشتریان جدید</h3>
                    <div class="chart-actions">
                        <select class="chart-period-select" data-chart="customers">
                            <option value="week">هفتگی</option>
                            <option value="month" selected>ماهانه</option>
                            <option value="year">سالانه</option>
                        </select>
                    </div>
                </div>
                <div id="customerChart"></div>
            </div>
        </div>

        <!-- Recent Section -->
        <main class="main-content">
            <div class="content-wrapper">
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">آخرین فاکتورها</h3>
                    <a href="?page=invoices" class="view-all">مشاهده همه</a>
                </div>
                <div class="table-responsive">
                    <table class="table recent-table">
                        <thead>
                            <tr>
                                <th>شماره فاکتور</th>
                                <th>مشتری</th>
                                <th>مبلغ (تومان)</th>
                                <th>تاریخ</th>
                                <th>وضعیت</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($recentInvoices as $invoice): ?>
                            <tr>
                                <td>#<?php echo $invoice['invoice_number']; ?></td>
                                <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                                <td><?php echo number_format($invoice['total_amount']); ?></td>
                                <td><?php echo jdate('Y/m/d', strtotime($invoice['created_at'])); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $invoice['status']; ?>">
                                        <?php
                                        $statusLabels = [
                                            'completed' => 'تکمیل شده',
                                            'pending' => 'در انتظار',
                                            'cancelled' => 'لغو شده'
                                        ];
                                        echo $statusLabels[$invoice['status']] ?? $invoice['status'];
                                        ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="table-card">
                <div class="table-header">
                    <h3 class="table-title">اعلان‌های اخیر</h3>
                </div>
                <div class="notifications-list">
                    <?php foreach ($recentNotifications as $notification): ?>
                        <div class="notification-card">
                            <div class="notification-icon" style="background: <?php echo $notification['color']; ?>">
                                <i class="<?php echo $notification['icon']; ?>"></i>
                            </div>
                            <div class="notification-info">
                                <h4><?php echo htmlspecialchars($notification['title']); ?></h4>
                                <p><?php echo htmlspecialchars($notification['message']); ?></p>
                                <span class="notification-time">
                                    <?php echo jdate('Y/m/d H:i', strtotime($notification['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php'; ?>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="assets/js/dashboard.js"></script>
</body>
</html>