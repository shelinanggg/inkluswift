// Menu Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentSearch = '';
    let currentCategory = '';
    let isEditMode = false;
    let editingMenuId = null;
    let categories = [];

    // DOM elements
    const searchInput = document.getElementById('searchInput');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const menuForm = document.getElementById('menuForm');
    const menuItemsContainer = document.getElementById('menuItemsContainer');
    const addMenuBtn = document.getElementById('addMenuBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const totalItemsSpan = document.getElementById('totalItems');
    const paginationButtons = document.getElementById('paginationButtons');
    const imageUpload = document.getElementById('imageUpload');
    const uploadBtn = document.getElementById('uploadBtn');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // CSRF token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Debug: Check if elements exist
    console.log('DOM Elements Check:');
    console.log('addMenuBtn:', addMenuBtn);
    console.log('menuForm:', menuForm);
    console.log('saveBtn:', saveBtn);
    console.log('cancelBtn:', cancelBtn);
    console.log('csrfToken:', csrfToken);

    // Initialize
    init();

    function init() {
        loadMenus();
        loadCategories();
        setupEventListeners();
    }

    function setupEventListeners() {
        // Debug: Check if addMenuBtn exists
        console.log('Setting up event listeners...');
        console.log('addMenuBtn element:', addMenuBtn);
        
        // Search functionality
        if (searchInput) {
            searchInput.addEventListener('input', debounce(function() {
                currentSearch = this.value;
                currentPage = 1;
                loadMenus();
            }, 500));
        }

        // Filter dropdown
        if (filterDropdown) {
            filterDropdown.addEventListener('click', function() {
                toggleCategoryDropdown();
            });
        }

        // Items per page
        if (itemsPerPageSelect) {
            itemsPerPageSelect.addEventListener('change', function() {
                itemsPerPage = parseInt(this.value);
                currentPage = 1;
                loadMenus();
            });
        }

        // Add menu button - with multiple approaches
        if (addMenuBtn) {
            console.log('Adding click event to addMenuBtn');
            addMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add menu button clicked!');
                showAddForm();
            });
            
            // Alternative using onclick
            addMenuBtn.onclick = function(e) {
                e.preventDefault();
                console.log('Add menu button clicked via onclick!');
                showAddForm();
            };
        } else {
            console.error('addMenuBtn not found!');
        }

        // Save button
        if (saveBtn) {
            saveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Save button clicked');
                saveMenu();
            });
        }

        // Cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Cancel button clicked');
                hideForm();
            });
        }

        // Image upload
        if (uploadBtn && imageUpload) {
            uploadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                imageUpload.click();
            });

            imageUpload.addEventListener('change', function() {
                handleImageUpload(this);
            });
        }

        // Remove image button
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function(e) {
                e.preventDefault();
                removeImage();
            });
        }

        // Click outside to close dropdown
        document.addEventListener('click', function(e) {
            if (filterDropdown && !filterDropdown.contains(e.target)) {
                closeCategoryDropdown();
            }
        });
    }

    // Load menus from server
    function loadMenus() {
        const params = new URLSearchParams({
            search: currentSearch,
            category: currentCategory,
            perPage: itemsPerPage,
            page: currentPage
        });

        fetch(`/api/menus?${params}`)
            .then(response => response.json())
            .then(data => {
                displayMenus(data.menus);
                updatePagination(data.totalItems);
                totalItemsSpan.textContent = data.totalItems;
            })
            .catch(error => {
                console.error('Error loading menus:', error);
                showError('Failed to load menus');
            });
    }

    // Load categories for filter dropdown
    function loadCategories() {
        fetch('/api/menu-categories')
            .then(response => response.json())
            .then(data => {
                categories = data.categories;
                updateCategoryDropdown();
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
    }

    // Display menus in table
    function displayMenus(menus) {
        if (menus.length === 0) {
            menuItemsContainer.innerHTML = '<div class="no-data">No menus found. Click "Add New Menu" to create one.</div>';
            return;
        }

        const menuHTML = menus.map(menu => `
            <div class="menu-item" data-menu-id="${menu.menu_id}">
                <div>${menu.menu_id}</div>
                <div>
                    ${menu.image ? 
                        `<img src="/storage/${menu.image}" alt="${menu.menu_name}" class="menu-image">` : 
                        `<div class="menu-image no-image">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>`
                    }
                </div>
                <div>${menu.menu_name}</div>
                <div class="price-display">Rp ${formatNumber(menu.price)}</div>
                <div class="stock-display">
                    <span class="stock-badge ${menu.stock <= 0 ? 'out-of-stock' : menu.stock <= 10 ? 'low-stock' : 'in-stock'}">
                        ${menu.stock}
                    </span>
                </div>
                <div class="discount-display">${menu.discount ? menu.discount + '%' : '-'}</div>
                <div><span class="category-badge">${menu.category}</span></div>
                <div>${menu.storage || '-'}</div>
                <div class="action-buttons">
                    <button class="edit-btn" onclick="editMenu('${menu.menu_id}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </button>
                    <button class="delete-btn" onclick="deleteMenu('${menu.menu_id}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');

        menuItemsContainer.innerHTML = menuHTML;
    }

    // Update pagination
    function updatePagination(totalItems) {
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        
        let paginationHTML = '';
        
        // Previous button
        paginationHTML += `
            <button class="pagination-btn ${currentPage <= 1 ? 'disabled' : ''}" 
                    onclick="changePage(${currentPage - 1})" 
                    ${currentPage <= 1 ? 'disabled' : ''}>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
        `;

        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            paginationHTML += `
                <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                        onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        }

        // Next button
        paginationHTML += `
            <button class="pagination-btn ${currentPage >= totalPages ? 'disabled' : ''}" 
                    onclick="changePage(${currentPage + 1})" 
                    ${currentPage >= totalPages ? 'disabled' : ''}>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        `;

        paginationButtons.innerHTML = paginationHTML;
    }

    // Category dropdown functions
    function updateCategoryDropdown() {
        const existingDropdown = document.querySelector('.category-options');
        if (existingDropdown) {
            existingDropdown.remove();
        }

        const dropdown = document.createElement('div');
        dropdown.className = 'category-options';
        dropdown.style.display = 'none';
        dropdown.style.position = 'absolute';
        dropdown.style.top = '100%';
        dropdown.style.left = '0';
        dropdown.style.marginTop = '5px';

        // All categories option
        const allOption = document.createElement('div');
        allOption.className = 'category-option';
        allOption.textContent = 'All Categories';
        allOption.addEventListener('click', function() {
            selectCategory('');
        });
        dropdown.appendChild(allOption);

        // Individual categories
        categories.forEach(category => {
            const option = document.createElement('div');
            option.className = 'category-option';
            option.textContent = category;
            option.addEventListener('click', function() {
                selectCategory(category);
            });
            dropdown.appendChild(option);
        });

        filterDropdown.style.position = 'relative';
        filterDropdown.appendChild(dropdown);
    }

    function toggleCategoryDropdown() {
        const dropdown = document.querySelector('.category-options');
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
    }

    function closeCategoryDropdown() {
        const dropdown = document.querySelector('.category-options');
        if (dropdown) {
            dropdown.style.display = 'none';
        }
    }

    function selectCategory(category) {
        currentCategory = category;
        currentPage = 1;
        
        const spanText = filterDropdown.querySelector('span');
        spanText.textContent = category ? `Category: ${category}` : 'Filter by category';
        
        closeCategoryDropdown();
        loadMenus();
    }

    // Form functions
    function showAddForm() {
        console.log('showAddForm called');
        isEditMode = false;
        editingMenuId = null;
        clearForm();
        
        if (menuForm) {
            menuForm.style.display = 'block';
            console.log('Form shown');
        } else {
            console.error('menuForm element not found');
        }
        
        if (saveBtn) {
            saveBtn.textContent = 'SAVE';
        }
        
        // Scroll to form
        if (menuForm) {
            menuForm.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function showEditForm(menu) {
        isEditMode = true;
        editingMenuId = menu.menu_id;
        fillForm(menu);
        menuForm.style.display = 'block';
        saveBtn.textContent = 'UPDATE';
        menuForm.scrollIntoView({ behavior: 'smooth' });
    }

    function hideForm() {
        menuForm.style.display = 'none';
        clearForm();
        isEditMode = false;
        editingMenuId = null;
    }

    function clearForm() {
        document.getElementById('menuId').value = '';
        document.getElementById('menuName').value = '';
        document.getElementById('menuPrice').value = '';
        document.getElementById('menuStock').value = '';
        document.getElementById('menuDiscount').value = '';
        document.getElementById('menuCategory').value = '';
        document.getElementById('menuStorage').value = '';
        document.getElementById('menuDescription').value = '';
        document.getElementById('menuIngredients').value = '';
        imageUpload.value = '';
        removeImage();
    }

    function fillForm(menu) {
        document.getElementById('menuId').value = menu.menu_id;
        document.getElementById('menuName').value = menu.menu_name;
        document.getElementById('menuPrice').value = menu.price;
        document.getElementById('menuStock').value = menu.stock || 0;
        document.getElementById('menuDiscount').value = menu.discount || '';
        document.getElementById('menuCategory').value = menu.category;
        document.getElementById('menuStorage').value = menu.storage || '';
        document.getElementById('menuDescription').value = menu.description || '';
        document.getElementById('menuIngredients').value = menu.ingredients || '';
        
        if (menu.image) {
            previewImage.src = `/storage/${menu.image}`;
            imagePreview.classList.add('has-image');
        }
    }

    // Image handling
    function handleImageUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                imagePreview.classList.add('has-image');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        imageUpload.value = '';
        previewImage.src = '';
        imagePreview.classList.remove('has-image');
    }

    // Save menu
    function saveMenu() {
        const formData = new FormData();
        
        // Basic fields
        formData.append('menu_name', document.getElementById('menuName').value);
        formData.append('price', document.getElementById('menuPrice').value);
        formData.append('stock', document.getElementById('menuStock').value || 0);
        formData.append('discount', document.getElementById('menuDiscount').value || 0);
        formData.append('category', document.getElementById('menuCategory').value);
        formData.append('storage', document.getElementById('menuStorage').value);
        formData.append('description', document.getElementById('menuDescription').value);
        formData.append('ingredients', document.getElementById('menuIngredients').value);
        
        // Image file
        if (imageUpload.files[0]) {
            formData.append('image', imageUpload.files[0]);
        }

        // CSRF token
        formData.append('_token', csrfToken);

        const url = isEditMode ? `/api/menus/${editingMenuId}` : '/api/menus';
        const method = isEditMode ? 'POST' : 'POST';
        
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                showValidationErrors(data.errors);
            } else {
                showSuccess(data.message);
                hideForm();
                loadMenus();
                loadCategories(); // Reload categories in case new category was added
            }
        })
        .catch(error => {
            console.error('Error saving menu:', error);
            showError('Failed to save menu');
        });
    }

    // Global functions for onclick handlers
    window.editMenu = function(menuId) {
        fetch(`/api/menus/${menuId}`)
            .then(response => response.json())
            .then(data => {
                if (data.menu) {
                    showEditForm(data.menu);
                } else {
                    showError('Menu not found');
                }
            })
            .catch(error => {
                console.error('Error loading menu:', error);
                showError('Failed to load menu details');
            });
    };

    window.deleteMenu = function(menuId) {
        if (confirm('Are you sure you want to delete this menu?')) {
            fetch(`/api/menus/${menuId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showSuccess(data.message);
                loadMenus();
            })
            .catch(error => {
                console.error('Error deleting menu:', error);
                showError('Failed to delete menu');
            });
        }
    };

    window.changePage = function(page) {
        if (page < 1) return;
        currentPage = page;
        loadMenus();
    };

    // Utility functions
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showSuccess(message) {
        // Create and show success notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            document.body.removeChild(notification);
        }, 3000);
    }

    function showError(message) {
        // Create and show error notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            document.body.removeChild(notification);
        }, 5000);
    }

    function showValidationErrors(errors) {
        let errorMessage = 'Validation errors:\n';
        for (const field in errors) {
            errorMessage += `${field}: ${errors[field].join(', ')}\n`;
        }
        alert(errorMessage);
    }
});