document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const filterDropdown = document.querySelector('.filter-dropdown');
    const accountForm = document.getElementById('accountForm');
    const accountItemsContainer = document.getElementById('accountItemsContainer');
    const paginationButtons = document.getElementById('paginationButtons');
    const itemsPerPageSelect = document.getElementById('itemsPerPage');
    const totalItemsSpan = document.getElementById('totalItems');
    const addAccountBtn = document.getElementById('addAccountBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const uploadBtn = document.getElementById('uploadBtn');
    const imageUpload = document.getElementById('imageUpload');
    const previewImage = document.getElementById('previewImage');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // State variables
    let currentPage = 1;
    let itemsPerPage = parseInt(itemsPerPageSelect.value);
    let totalItems = 0;
    let searchTerm = '';
    let selectedRole = '';
    let isEditing = false;
    let selectedImageFile = null;

    // Initialize
    loadAccounts();
    setupEventListeners();

    function setupEventListeners() {
        // Search input
        searchInput.addEventListener('input', function() {
            searchTerm = this.value;
            currentPage = 1;
            loadAccounts();
        });

        // Filter dropdown
        filterDropdown.addEventListener('click', function() {
            const options = ['All', 'Admin', 'Staff', 'Customer'];
            const dropdown = document.createElement('div');
            dropdown.className = 'role-options';
            
            options.forEach(option => {
                const item = document.createElement('div');
                item.className = 'role-option';
                item.textContent = option;
                item.addEventListener('click', function() {
                    selectedRole = option === 'All' ? '' : option.toLowerCase();
                    filterDropdown.querySelector('span').textContent = 'Filter by role: ' + option;
                    dropdown.remove();
                    currentPage = 1;
                    loadAccounts();
                });
                dropdown.appendChild(item);
            });
            
            document.body.appendChild(dropdown);
            const rect = filterDropdown.getBoundingClientRect();
            dropdown.style.top = (rect.bottom + window.scrollY) + 'px';
            dropdown.style.left = (rect.left + window.scrollX) + 'px';
            
            const removeDropdown = function(e) {
                if (!dropdown.contains(e.target) && e.target !== filterDropdown) {
                    dropdown.remove();
                    document.removeEventListener('click', removeDropdown);
                }
            };
            
            setTimeout(() => {
                document.addEventListener('click', removeDropdown);
            }, 0);
        });

        // Items per page
        itemsPerPageSelect.addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            loadAccounts();
        });

        // Add account button
        addAccountBtn.addEventListener('click', function() {
            showAccountForm(false);
        });

        // Save button
        saveBtn.addEventListener('click', saveAccount);

        // Cancel button
        cancelBtn.addEventListener('click', hideAccountForm);

        // Upload button
        uploadBtn.addEventListener('click', function() {
            imageUpload.click();
        });

        // Image upload - FIXED
        imageUpload.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                selectedImageFile = this.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('imagePreview');
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    removeImageBtn.style.display = 'flex';
                    
                    // Tambahkan class has-image untuk menyembunyikan SVG
                    imagePreview.classList.add('has-image');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Remove image - FIXED
        removeImageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const imagePreview = document.getElementById('imagePreview');
            
            previewImage.src = '';
            previewImage.style.display = 'none';
            removeImageBtn.style.display = 'none';
            imageUpload.value = '';
            selectedImageFile = null;
            
            // Hapus class has-image agar SVG muncul kembali
            imagePreview.classList.remove('has-image');
        });
    }

    function loadAccounts() {
        fetch(`/api/accounts?page=${currentPage}&perPage=${itemsPerPage}&search=${searchTerm}&role=${selectedRole}`)
            .then(response => response.json())
            .then(data => {
                displayAccounts(data.users);
                updatePagination(data.totalItems);
            })
            .catch(error => {
                console.error('Error loading accounts:', error);
                accountItemsContainer.innerHTML = '<div class="error-message">Failed to load accounts. Please try again.</div>';
            });
    }

    function displayAccounts(accounts) {
        accountItemsContainer.innerHTML = '';
        
        if (accounts.length === 0) {
            accountItemsContainer.innerHTML = '<div class="no-data">No accounts found. Click "Add New Account" to create one.</div>';
            return;
        }
        
        accounts.forEach(account => {
            const accountItem = document.createElement('div');
            accountItem.className = 'account-item';
            
            const statusClass = account.status === 'active' ? 'status-active' : 'status-inactive';
            
            accountItem.innerHTML = `
                <div>${account.user_id}</div>
                <div>${account.name}</div>
                <div>${account.email}</div>
                <div>${account.phone || '-'}</div>
                <div class="role-badge role-${account.role}">${account.role}</div>
                <div class="status-badge ${statusClass}">${account.status}</div>
                <div>${formatDate(account.join_date)}</div>
                <div class="action-buttons">
                    <button class="edit-btn" data-id="${account.user_id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <button class="delete-btn" data-id="${account.user_id}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>
                </div>
            `;
            
            accountItemsContainer.appendChild(accountItem);
            
            // Add event listeners to edit and delete buttons
            accountItem.querySelector('.edit-btn').addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                editAccount(userId);
            });
            
            accountItem.querySelector('.delete-btn').addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                deleteAccount(userId);
            });
        });
    }

    function updatePagination(total) {
        totalItems = total;
        totalItemsSpan.textContent = total;
        
        const totalPages = Math.ceil(total / itemsPerPage);
        
        paginationButtons.innerHTML = '';
        
        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'pagination-btn prev' + (currentPage === 1 ? ' disabled' : '');
        prevBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                loadAccounts();
            }
        });
        paginationButtons.appendChild(prevBtn);
        
        // Page buttons
        const maxButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);
        
        if (endPage - startPage + 1 < maxButtons) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'pagination-btn' + (i === currentPage ? ' active' : '');
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', function() {
                currentPage = i;
                loadAccounts();
            });
            paginationButtons.appendChild(pageBtn);
        }
        
        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'pagination-btn next' + (currentPage === totalPages ? ' disabled' : '');
        nextBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        nextBtn.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                loadAccounts();
            }
        });
        paginationButtons.appendChild(nextBtn);
    }

    function showAccountForm(isEdit) {
        accountForm.style.display = 'block';
        isEditing = isEdit;
        
        if (!isEdit) {
            // Reset form for new account
            document.getElementById('userId').value = '';
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPhone').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userConfirmPassword').value = '';
            document.getElementById('userRole').value = 'customer';
            document.getElementById('userStatus').value = 'active';
            document.getElementById('userAddress').value = '';
            
            // FIXED - Reset image preview
            const imagePreview = document.getElementById('imagePreview');
            previewImage.src = '';
            previewImage.style.display = 'none';
            removeImageBtn.style.display = 'none';
            imageUpload.value = '';
            selectedImageFile = null;
            imagePreview.classList.remove('has-image'); // Hapus class has-image
        }
        
        // Scroll to form
        accountForm.scrollIntoView({ behavior: 'smooth' });
    }

    function hideAccountForm() {
        accountForm.style.display = 'none';
    }

    function editAccount(userId) {
        fetch(`/api/accounts/${userId}`)
            .then(response => response.json())
            .then(data => {
                const user = data.user;
                const imagePreview = document.getElementById('imagePreview');
                
                document.getElementById('userId').value = user.user_id;
                document.getElementById('userName').value = user.name;
                document.getElementById('userEmail').value = user.email;
                document.getElementById('userPhone').value = user.phone || '';
                document.getElementById('userPassword').value = '';
                document.getElementById('userConfirmPassword').value = '';
                document.getElementById('userRole').value = user.role;
                document.getElementById('userStatus').value = user.status;
                document.getElementById('userAddress').value = user.address || '';
                
                // FIXED - Handle existing profile picture
                if (user.profile_picture) {
                    selectedImageFile = null;
                    previewImage.src = `/storage/${user.profile_picture}`;
                    previewImage.style.display = 'block';
                    removeImageBtn.style.display = 'flex';
                    imagePreview.classList.add('has-image'); // Tambahkan class
                } else {
                    previewImage.src = '';
                    previewImage.style.display = 'none';
                    removeImageBtn.style.display = 'none';
                    imagePreview.classList.remove('has-image'); // Hapus class
                }
                
                showAccountForm(true);
            })
            .catch(error => {
                console.error('Error fetching user details:', error);
                alert('Failed to load user details. Please try again.');
            });
    }

    function saveAccount() {
        // Validasi form
        const userName = document.getElementById('userName').value;
        const userEmail = document.getElementById('userEmail').value;
        const userPassword = document.getElementById('userPassword').value;
        const userConfirmPassword = document.getElementById('userConfirmPassword').value;
        const userRole = document.getElementById('userRole').value;
        
        if (!userName || !userEmail || !userRole) {
            alert('Please fill in all required fields.');
            return;
        }
        
        if (!isEditing && !userPassword) {
            alert('Password is required for new account.');
            return;
        }
        
        if (userPassword && userPassword !== userConfirmPassword) {
            alert('Passwords do not match.');
            return;
        }
        
        // Prepare form data
        const formData = new FormData();
        formData.append('name', userName);
        formData.append('email', userEmail);
        formData.append('phone', document.getElementById('userPhone').value);
        formData.append('role', userRole);
        formData.append('status', document.getElementById('userStatus').value);
        formData.append('address', document.getElementById('userAddress').value);
        
        if (userPassword) {
            formData.append('password', userPassword);
        }
        
        if (selectedImageFile) {
            formData.append('profile_picture', selectedImageFile);
        }
        
        // CSRF token for Laravel
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // URL and method based on whether creating or editing
        const userId = document.getElementById('userId').value;
        const url = isEditing ? `/api/accounts/${userId}` : '/api/accounts';
        const method = isEditing ? 'PUT' : 'POST';
        
        // If using PUT method for update, Laravel requires _method field
        if (isEditing) {
            formData.append('_method', 'PUT');
        }
        
        // Make API request
        fetch(url, {
            method: isEditing ? 'POST' : method, // Use POST with _method for PUT
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat().join('\n');
                alert('Validation error:\n' + errorMessages);
                return;
            }
            
            hideAccountForm();
            loadAccounts();
            alert(isEditing ? 'Account updated successfully!' : 'Account created successfully!');
        })
        .catch(error => {
            console.error('Error saving account:', error);
            alert('Failed to save account. Please try again.');
        });
    }

    function deleteAccount(userId) {
        if (!confirm('Are you sure you want to delete this account?')) {
            return;
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/api/accounts/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            loadAccounts();
            alert('Account deleted successfully!');
        })
        .catch(error => {
            console.error('Error deleting account:', error);
            alert('Failed to delete account. Please try again.');
        });
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }
});