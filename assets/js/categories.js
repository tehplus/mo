/**
 * اسکریپت مدیریت دسته‌بندی‌ها
 * این فایل شامل توابع اصلی مدیریت دسته‌بندی‌هاست
 * 
 * @author tehplus
 * @version 1.0.0
 * @since 2025-05-02 06:56:54
 */

document.addEventListener('DOMContentLoaded', function() {
    // متغیرهای سراسری
    const categoriesTree = $('#jstree');
    const categoryForm = document.getElementById('categoryForm');
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
    let currentCategoryId = null;
    
    // راه‌اندازی درخت دسته‌بندی‌ها با jstree
    if (categoriesTree.length) {
        categoriesTree.jstree({
            core: {
                themes: {
                    name: 'default',
                    dots: false,
                    icons: true
                },
                check_callback: true,
                data: {
                    url: `${BASE_URL}/api/categories/tree`,
                    dataType: 'json'
                }
            },
            plugins: [
                'dnd',        // Drag & Drop
                'wholerow',   // انتخاب کل ردیف
                'contextmenu', // منوی راست کلیک
                'search',     // جستجو
                'state',      // ذخیره وضعیت
                'types'       // انواع گره
            ],
            contextmenu: {
                items: customContextMenu
            },
            types: {
                default: {
                    icon: 'bi bi-folder'
                },
                root: {
                    icon: 'bi bi-diagram-3'
                }
            }
        }).on('move_node.jstree', handleNodeMove)
          .on('select_node.jstree', handleNodeSelect);

        // راه‌اندازی جستجو
        const searchTimeout = 300;
        let searchTimer = null;

        $('#searchCategory').on('keyup', function() {
            if (searchTimer) {
                clearTimeout(searchTimer);
            }
            
            const searchString = $(this).val();
            searchTimer = setTimeout(() => {
                categoriesTree.jstree('search', searchString);
            }, searchTimeout);
        });
    }

    // منوی راست کلیک سفارشی
    function customContextMenu(node) {
        return {
            create: {
                label: 'افزودن زیر دسته',
                icon: 'bi bi-plus-lg',
                action: function() {
                    openCategoryModal('create', node.id);
                }
            },
            edit: {
                label: 'ویرایش',
                icon: 'bi bi-pencil',
                action: function() {
                    openCategoryModal('edit', node.id);
                }
            },
            delete: {
                label: 'حذف',
                icon: 'bi bi-trash',
                action: function() {
                    openDeleteModal(node.id);
                }
            }
        };
    }

    // مدیریت جابجایی گره‌ها
    function handleNodeMove(e, data) {
        const nodeId = data.node.id;
        const newParentId = data.parent;
        const position = data.position;

        $.ajax({
            url: `${BASE_URL}/api/categories/move`,
            method: 'POST',
            data: {
                id: nodeId,
                parent_id: newParentId,
                position: position
            },
            success: function(response) {
                if (response.success) {
                    showToast('success', 'دسته‌بندی با موفقیت جابجا شد');
                } else {
                    showToast('error', 'خطا در جابجایی دسته‌بندی');
                    categoriesTree.jstree('refresh');
                }
            },
            error: function() {
                showToast('error', 'خطا در برقراری ارتباط با سرور');
                categoriesTree.jstree('refresh');
            }
        });
    }

    // مدیریت انتخاب گره
    function handleNodeSelect(e, data) {
        const node = data.node;
        // می‌توانید اطلاعات بیشتر دسته‌بندی را اینجا نمایش دهید
    }

    // باز کردن مودال دسته‌بندی
    function openCategoryModal(mode, id = null) {
        currentCategoryId = id;
        const modalTitle = document.getElementById('categoryModalTitle');
        const form = document.getElementById('categoryForm');
        
        // تنظیم عنوان مودال
        modalTitle.textContent = mode === 'create' ? 'افزودن دسته‌بندی جدید' : 'ویرایش دسته‌بندی';
        
        if (mode === 'edit' && id) {
            // دریافت اطلاعات دسته‌بندی
            $.ajax({
                url: `${BASE_URL}/api/categories/${id}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        fillFormWithData(response.data);
                        categoryModal.show();
                    } else {
                        showToast('error', 'خطا در دریافت اطلاعات دسته‌بندی');
                    }
                },
                error: function() {
                    showToast('error', 'خطا در برقراری ارتباط با سرور');
                }
            });
        } else {
            form.reset();
            if (id) {
                document.getElementById('categoryParent').value = id;
            }
            categoryModal.show();
        }
    }

    // پر کردن فرم با داده‌ها
    function fillFormWithData(data) {
        const form = document.getElementById('categoryForm');
        for (const [key, value] of Object.entries(data)) {
            const input = form.elements[key];
            if (input) {
                if (input.type === 'radio') {
                    const radio = form.querySelector(`input[name="${key}"][value="${value}"]`);
                    if (radio) radio.checked = true;
                } else if (input.type === 'file') {
                    if (value) {
                        document.getElementById('imagePreview').querySelector('img').src = value;
                    }
                } else {
                    input.value = value;
                }
            }
        }
    }

    // مدیریت فرم
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const formData = new FormData(this);
            const url = currentCategoryId ? 
                `${BASE_URL}/api/categories/${currentCategoryId}` : 
                `${BASE_URL}/api/categories`;
                        const method = currentCategoryId ? 'PUT' : 'POST';

            // ارسال درخواست به سرور
            $.ajax({
                url: url,
                method: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showToast('success', currentCategoryId ? 
                            'دسته‌بندی با موفقیت ویرایش شد' : 
                            'دسته‌بندی با موفقیت ایجاد شد'
                        );
                        categoryModal.hide();
                        categoriesTree.jstree('refresh');
                        updateStatistics(); // بروزرسانی آمار
                    } else {
                        showToast('error', response.message || 'خطا در ثبت اطلاعات');
                    }
                },
                error: function(xhr) {
                    const error = xhr.responseJSON?.message || 'خطا در برقراری ارتباط با سرور';
                    showToast('error', error);
                }
            });
        });
    }

    // باز کردن مودال حذف
    function openDeleteModal(id) {
        currentCategoryId = id;
        const node = categoriesTree.jstree(true).get_node(id);
        
        if (node.children.length > 0) {
            Swal.fire({
                title: 'هشدار!',
                text: 'این دسته‌بندی دارای زیرمجموعه است. آیا از حذف آن و تمام زیرمجموعه‌هایش اطمینان دارید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteCategory(id);
                }
            });
        } else {
            deleteModal.show();
        }
    }

    // حذف دسته‌بندی
    function deleteCategory(id) {
        $.ajax({
            url: `${BASE_URL}/api/categories/${id}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showToast('success', 'دسته‌بندی با موفقیت حذف شد');
                    deleteModal.hide();
                    categoriesTree.jstree('refresh');
                    updateStatistics(); // بروزرسانی آمار
                } else {
                    showToast('error', response.message || 'خطا در حذف دسته‌بندی');
                }
            },
            error: function() {
                showToast('error', 'خطا در برقراری ارتباط با سرور');
            }
        });
    }

    // تایید حذف از طریق مودال
    document.getElementById('confirmDelete')?.addEventListener('click', function() {
        deleteCategory(currentCategoryId);
    });

    // بروزرسانی آمار
    function updateStatistics() {
        $.ajax({
            url: `${BASE_URL}/api/categories/statistics`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    updateStatCard('mainCategories', response.data.mainCategories);
                    updateStatCard('subCategories', response.data.subCategories);
                    updateStatCard('totalProducts', response.data.totalProducts);
                    updateStatCard('activeCategories', response.data.activeCategories);
                }
            }
        });
    }

    // بروزرسانی کارت آمار
    function updateStatCard(id, value) {
        const element = document.querySelector(`.stat-card[data-id="${id}"] h3`);
        if (element) {
            element.textContent = value;
            element.style.animation = 'none';
            element.offsetHeight; // trigger reflow
            element.style.animation = null;
            element.classList.add('number-updated');
        }
    }

    // مدیریت پیش‌نمایش تصویر
    const imageInput = document.getElementById('categoryImage');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImage');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        removeImageBtn?.addEventListener('click', function() {
            imageInput.value = '';
            imagePreview.querySelector('img').src = `${BASE_URL}/assets/images/placeholder.jpg`;
        });
    }

    // مدیریت slug خودکار
    const nameInput = document.getElementById('categoryName');
    const slugInput = document.getElementById('categorySlug');

    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function() {
            if (!slugInput.value) { // فقط اگر slug خالی است
                slugInput.value = createSlug(this.value);
            }
        });

        slugInput.addEventListener('input', function() {
            this.value = createSlug(this.value);
        });
    }

    // تابع ساخت slug
    function createSlug(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')        // فاصله‌ها به خط تیره
            .replace(/[^\w\-]+/g, '')    // حذف کاراکترهای غیرمجاز
            .replace(/\-\-+/g, '-')      // خط تیره‌های تکراری
            .replace(/^-+/, '')          // حذف خط تیره از ابتدا
            .replace(/-+$/, '');         // حذف خط تیره از انتها
    }

    // دکمه‌های گسترش/جمع کردن همه
    document.getElementById('expandAllBtn')?.addEventListener('click', function() {
        categoriesTree.jstree('open_all');
    });

    document.getElementById('collapseAllBtn')?.addEventListener('click', function() {
        categoriesTree.jstree('close_all');
    });

    // نمایش toast
    function showToast(type, message) {
        const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
        const className = type === 'success' ? 'bg-success' : 'bg-danger';

        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: 'left',
            className: className,
            close: true,
            avatar: `<i class="bi bi-${icon}"></i>`,
            stopOnFocus: true
        }).showToast();
    }

    // تغییر نوع نمایش
    document.querySelectorAll('.view-options .btn')?.forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.view-options .btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const viewType = this.dataset.view;
            changeViewType(viewType);
        });
    });

    // تغییر نوع نمایش دسته‌بندی‌ها
    function changeViewType(type) {
        const container = document.querySelector('.categories-container');
        container.className = 'categories-container';
        container.classList.add(`view-${type}`);
        localStorage.setItem('categories_view', type);
    }

    // بازیابی نوع نمایش ذخیره شده
    const savedView = localStorage.getItem('categories_view');
    if (savedView) {
        const viewBtn = document.querySelector(`.view-options .btn[data-view="${savedView}"]`);
        if (viewBtn) {
            viewBtn.click();
        }
    }
});