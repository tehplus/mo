<?php
/**
 * صفحه مدیریت دسته‌بندی‌ها
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 08:01:16
 */

// بررسی دسترسی
if (!checkUserPermission('manage_categories')) {
    redirect('error/403');
}

// تنظیم عنوان صفحه
$page_title = 'مدیریت دسته‌بندی‌ها';
$page_css = 'categories';
?>

<!-- شروع محتوای اصلی -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-folder-tree"></i>
                    مدیریت دسته‌بندی‌ها
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- کارت‌های آمار -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="mainCategoriesCount">0</h3>
                        <p>دسته‌های اصلی</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="subCategoriesCount">0</h3>
                        <p>زیر دسته‌ها</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder-tree"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="totalProductsCount">0</h3>
                        <p>محصولات</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="inactiveCategoriesCount">0</h3>
                        <p>دسته‌های غیرفعال</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-folder-minus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- بخش اصلی مدیریت -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">ساختار دسته‌بندی‌ها</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-toggle="tooltip" title="تازه‌سازی">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="categoriesTree"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">مشخصات دسته‌بندی</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                <i class="fas fa-plus"></i>
                                افزودن دسته‌بندی
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="categoryDetails">
                            <div class="text-center text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>لطفاً یک دسته‌بندی را انتخاب کنید</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال افزودن دسته‌بندی -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن دسته‌بندی جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">نام دسته‌بندی</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryParent" class="form-label">دسته‌بندی والد</label>
                        <select class="form-select" id="categoryParent">
                            <option value="0">بدون والد (دسته اصلی)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">توضیحات</label>
                        <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="categoryStatus" checked>
                            <label class="form-check-label" for="categoryStatus">فعال</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-primary" id="saveCategoryBtn">ذخیره</button>
            </div>
        </div>
    </div>
    <!-- کتابخانه‌های مورد نیاز -->
<!-- کتابخانه‌ها و استایل‌های مورد نیاز -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/categories.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/plugins/jstree/themes/default/style.min.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/plugins/sweetalert2/sweetalert2.min.css">

<script src="<?php echo BASE_URL; ?>/assets/plugins/jstree/jstree.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- اسکریپت‌های مخصوص صفحه -->
<script>
// تعریف متغیرهای سراسری
const baseUrl = '<?php echo BASE_URL; ?>';
</script>
<script src="<?php echo BASE_URL; ?>/assets/js/categories.js"></script>
</div>