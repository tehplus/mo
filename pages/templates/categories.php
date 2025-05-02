<?php
/**
 * صفحه مدیریت دسته‌بندی‌ها
 * این صفحه شامل لیست درختی دسته‌بندی‌ها و امکانات مدیریتی آنهاست
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 06:56:54
 */

// بررسی دسترسی کاربر
if (!checkUserPermission('manage_categories')) {
    redirect('error/403');
}

// دریافت لیست دسته‌بندی‌ها
$categories = getCategoriesTree();
?>

<div class="content-wrapper">
    <!-- هدر صفحه -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="bi bi-diagram-3"></i>
                مدیریت دسته‌بندی‌ها
            </h1>
            <p class="page-subtitle">مدیریت و سازماندهی دسته‌بندی‌های محصولات</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn btn-primary" id="addCategoryBtn">
                <i class="bi bi-plus-lg"></i>
                افزودن دسته‌بندی جدید
            </button>
            <button type="button" class="btn btn-outline-secondary" id="expandAllBtn">
                <i class="bi bi-arrows-expand"></i>
                باز کردن همه
            </button>
            <button type="button" class="btn btn-outline-secondary" id="collapseAllBtn">
                <i class="bi bi-arrows-collapse"></i>
                بستن همه
            </button>
        </div>
    </div>

    <!-- نوار ابزار -->
    <div class="toolbar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchCategory" class="form-control" placeholder="جستجو در دسته‌بندی‌ها...">
        </div>
        <div class="view-options">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary active" data-view="tree">
                    <i class="bi bi-diagram-3"></i>
                    نمایش درختی
                </button>
                <button type="button" class="btn btn-outline-secondary" data-view="grid">
                    <i class="bi bi-grid"></i>
                    نمایش گرید
                </button>
                <button type="button" class="btn btn-outline-secondary" data-view="list">
                    <i class="bi bi-list-ul"></i>
                    نمایش لیستی
                </button>
            </div>
        </div>
    </div>

    <!-- بخش اصلی -->
    <div class="main-content">
        <!-- کارت‌های آمار -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-folder"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo countMainCategories(); ?></h3>
                    <p>دسته‌بندی اصلی</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-diagram-2"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo countSubCategories(); ?></h3>
                    <p>زیر دسته</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo countTotalProducts(); ?></h3>
                    <p>محصول</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo getActiveCategoriesCount(); ?></h3>
                    <p>دسته‌بندی فعال</p>
                </div>
            </div>
        </div>

        <!-- درخت دسته‌بندی‌ها -->
        <div class="categories-container">
            <div class="card">
                <div class="card-body">
                    <div id="categoriesTree" class="categories-tree">
                        <?php if (empty($categories)): ?>
                            <div class="empty-state">
                                <img src="<?php echo BASE_URL; ?>/assets/images/empty-category.svg" alt="بدون دسته‌بندی">
                                <h3>هیچ دسته‌بندی وجود ندارد</h3>
                                <p>برای شروع، یک دسته‌بندی جدید ایجاد کنید</p>
                                <button type="button" class="btn btn-primary" id="createFirstCategory">
                                    <i class="bi bi-plus-lg"></i>
                                    ایجاد اولین دسته‌بندی
                                </button>
                            </div>
                        <?php else: ?>
                            <div id="jstree"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال افزودن/ویرایش دسته‌بندی -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">افزودن دسته‌بندی جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm" class="needs-validation" novalidate>
                    <input type="hidden" id="categoryId" name="id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">نام دسته‌بندی</label>
                                <input type="text" class="form-control" id="categoryName" name="name" required>
                                <div class="invalid-feedback">
                                    لطفاً نام دسته‌بندی را وارد کنید
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="categorySlug" class="form-label">نامک (slug)</label>
                                <input type="text" class="form-control" id="categorySlug" name="slug" dir="ltr">
                                <div class="form-text">
                                    به صورت خودکار از روی نام ساخته می‌شود
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="categoryParent" class="form-label">دسته‌بندی والد</label>
                                <select class="form-select" id="categoryParent" name="parent_id">
                                    <option value="0">بدون والد (دسته‌بندی اصلی)</option>
                                    <?php echo buildCategoryOptions($categories); ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoryDescription" class="form-label">توضیحات</label>
                                <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="categoryImage" class="form-label">تصویر دسته‌بندی</label>
                                <div class="image-upload-wrapper">
                                    <div class="image-preview" id="imagePreview">
                                        <img src="<?php echo BASE_URL; ?>/assets/images/placeholder.jpg" alt="پیش‌نمایش">
                                    </div>
                                    <div class="image-upload-controls">
                                        <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
                                        <button type="button" class="btn btn-outline-danger btn-sm mt-2" id="removeImage">
                                            <i class="bi bi-trash"></i>
                                            حذف تصویر
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoryOrder" class="form-label">ترتیب نمایش</label>
                                <input type="number" class="form-control" id="categoryOrder" name="order" min="0" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label d-block">وضعیت</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                                    <label class="form-check-label" for="statusActive">فعال</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
                                    <label class="form-check-label" for="statusInactive">غیرفعال</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">متاتگ‌ها (اختیاری)</label>
                                <div class="meta-tags-wrapper">
                                    <div class="mb-2">
                                        <input type="text" class="form-control" id="metaTitle" name="meta_title" placeholder="عنوان متا">
                                    </div>
                                    <div class="mb-2">
                                        <textarea class="form-control" id="metaDescription" name="meta_description" rows="2" placeholder="توضیحات متا"></textarea>
                                    </div>
                                    <div>
                                        <input type="text" class="form-control" id="metaKeywords" name="meta_keywords" placeholder="کلمات کلیدی (با کاما جدا کنید)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="submit" class="btn btn-primary" form="categoryForm">ذخیره تغییرات</button>
            </div>
        </div>
    </div>
</div>

<!-- مودال حذف دسته‌بندی -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">حذف دسته‌بندی</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>آیا از حذف این دسته‌بندی اطمینان دارید؟</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    هشدار: با حذف دسته‌بندی، تمام زیردسته‌های آن نیز حذف خواهند شد.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bi bi-trash"></i>
                    حذف دسته‌بندی
                </button>
            </div>
        </div>
    </div>
</div>