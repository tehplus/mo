<?php
/**
 * صفحه مدیریت دسته‌بندی‌ها
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 10:49:49
 */

// بررسی دسترسی
if (!checkUserPermission('manage_categories')) {
    header('HTTP/1.1 403 Forbidden');
    echo "<h1>دسترسی غیرمجاز</h1>";
    echo "<p>شما اجازه دسترسی به این صفحه را ندارید.</p>";
    exit;
}

// تنظیم عنوان صفحه
$page_title = 'مدیریت دسته‌بندی‌ها';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?php echo $page_title; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                    <li class="breadcrumb-item"><a href="/?page=dashboard">داشبورد</a></li>
                    <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- محتوای صفحه اینجا قرار می‌گیرد -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">لیست دسته‌بندی‌ها</h3>
                <div class="card-tools">
                    <a href="/?page=categories&action=create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i>
                        افزودن دسته‌بندی جدید
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>عنوان</th>
                            <th>توضیحات</th>
                            <th style="width: 100px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>دسته‌بندی نمونه</td>
                            <td>توضیحات نمونه</td>
                            <td>
                                <a href="/?page=categories&action=edit&id=1" class="btn btn-info btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/?page=categories&action=delete&id=1" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>