// Global variables
let currentPage = 1;
let itemsPerPage = 10;
let totalItems = 0;
let isEditMode = false;
let currentEditId = null;
let searchTimeout = null;

// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// DOM elements
const elements = {
    searchInput: document.getElementById('searchInput'),
    itemsPerPageSelect: document.getElementById('itemsPerPage'),
    paymentForm: document.getElementById('paymentForm'),
    paymentItemsContainer: document.getElementById('paymentItemsContainer'),
    paginationButtons: document.getElementById('paginationButtons'),
    totalItemsSpan: document.getElementById('totalItems'),
    addPaymentBtn: document.getElementById('addPaymentBtn'),
    saveBtn: document.getElementById('saveBtn'),
    cancelBtn: document.getElementById('cancelBtn'),
    methodId: document.getElementById('methodId'),
    methodName: document.getElementById('methodName'),
    destinationAccount: document.getElementById('destinationAccount'),
    description: document.getElementById('description'),
    imageUpload: document.getElementById('imageUpload'),
    uploadBtn: document.getElementById('uploadBtn'),
    imagePreview: document.getElementById('imagePreview'),
    previewImage: document.getElementById('previewImage'),
    removeImageBtn: document.getElementById('removeImageBtn')
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    loadPayments();
});

// Initialize all event listeners
function initializeEventListeners() {
    // Search functionality
    elements.searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentPage = 1;
            loadPayments();
        }, 500);
    });

    // Items per page change
    elements.itemsPerPageSelect.addEventListener('change', function() {
        itemsPerPage = parseInt(this.value);
        currentPage = 1;
        loadPayments();
    });

    // Add payment button
    elements.addPaymentBtn.addEventListener('click', showAddForm);

    // Save button
    elements.saveBtn.addEventListener('click', savePayment);

    // Cancel button
    elements.cancelBtn.addEventListener('click', hideForm);

    // Image upload functionality
    elements.uploadBtn.addEventListener('click', () => elements.imageUpload.click());
    elements.imageUpload.addEventListener('change', handleImageUpload);
    elements.removeImageBtn.addEventListener('click', removeImage);
}

// Load payments with pagination and search
async function loadPayments() {
    try {
        showLoading();
        
        const searchTerm = elements.searchInput.value.trim();
        const params = new URLSearchParams({
            page: currentPage,
            perPage: itemsPerPage,
            search: searchTerm
        });

        const response = await fetch(`/api/payments?${params}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        displayPayments(data.payments);
        updatePagination(data.totalItems);
        
    } catch (error) {
        console.error('Error loading payments:', error);
        showError('Failed to load payment methods. Please try again.');
    }
}

// Display payments in the table
function displayPayments(payments) {
    if (!payments || payments.length === 0) {
        elements.paymentItemsContainer.innerHTML = '<div class="no-data">No payment methods found.</div>';
        return;
    }

    const paymentHtml = payments.map(payment => `
        <div class="payment-item">
            <div data-label="Method ID">${payment.method_id}</div>
            <div data-label="Method Name">${escapeHtml(payment.method_name)}</div>
            <div data-label="Description">${escapeHtml(payment.description || '-')}</div>
            <div data-label="Destination Account">${escapeHtml(payment.destination_account || '-')}</div>
            <div data-label="Static Proof" class="image-cell">
                ${payment.static_proof ? 
                    `<img src="/storage/${payment.static_proof}" alt="Payment Proof" class="image-thumbnail">` : 
                    '<div class="no-image">-</div>'
                }
            </div>
            <div data-label="Action" class="action-buttons">
                <button class="edit-btn" onclick="editPayment('${payment.method_id}')" title="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                    </svg>
                </button>
                <button class="delete-btn" onclick="deletePayment('${payment.method_id}')" title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="m19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');

    elements.paymentItemsContainer.innerHTML = paymentHtml;
}

// Update pagination controls
function updatePagination(total) {
    totalItems = total;
    elements.totalItemsSpan.textContent = total;

    const totalPages = Math.ceil(total / itemsPerPage);
    let paginationHtml = '';

    // Previous button
    paginationHtml += `
        <button class="pagination-btn ${currentPage === 1 ? 'disabled' : ''}" 
                onclick="changePage(${currentPage - 1})" 
                ${currentPage === 1 ? 'disabled' : ''}>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
    `;

    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
        paginationHtml += `
            <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                    onclick="changePage(${i})">
                ${i}
            </button>
        `;
    }

    // Next button
    paginationHtml += `
        <button class="pagination-btn ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}" 
                onclick="changePage(${currentPage + 1})" 
                ${currentPage === totalPages || totalPages === 0 ? 'disabled' : ''}>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>
    `;

    elements.paginationButtons.innerHTML = paginationHtml;
}

// Change page
function changePage(page) {
    if (page < 1 || page > Math.ceil(totalItems / itemsPerPage)) return;
    currentPage = page;
    loadPayments();
}

// Show add form
function showAddForm() {
    isEditMode = false;
    currentEditId = null;
    resetForm();
    elements.paymentForm.style.display = 'block';
    elements.saveBtn.textContent = 'SAVE';
    elements.methodName.focus();
}

// Show edit form
async function editPayment(methodId) {
    try {
        const response = await fetch(`/api/payments/${methodId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        const payment = data.payment;

        isEditMode = true;
        currentEditId = methodId;
        
        // Fill form with existing data
        elements.methodId.value = payment.method_id;
        elements.methodName.value = payment.method_name;
        elements.destinationAccount.value = payment.destination_account || '';
        elements.description.value = payment.description || '';

        // Handle existing image
        if (payment.static_proof) {
            elements.previewImage.src = `/storage/${payment.static_proof}`;
            elements.imagePreview.classList.add('has-image');
        } else {
            elements.imagePreview.classList.remove('has-image');
        }

        elements.paymentForm.style.display = 'block';
        elements.saveBtn.textContent = 'UPDATE';
        elements.methodName.focus();

    } catch (error) {
        console.error('Error loading payment details:', error);
        showError('Failed to load payment details. Please try again.');
    }
}

// Save payment (create or update)
async function savePayment() {
    if (!validateForm()) return;

    const formData = new FormData();
    formData.append('method_name', elements.methodName.value.trim());
    formData.append('description', elements.description.value.trim());
    formData.append('destination_account', elements.destinationAccount.value.trim());

    // Add image if selected
    if (elements.imageUpload.files[0]) {
        formData.append('static_proof', elements.imageUpload.files[0]);
    }

    try {
        elements.saveBtn.disabled = true;
        elements.saveBtn.textContent = isEditMode ? 'UPDATING...' : 'SAVING...';

        let url = '/api/payments';
        let method = 'POST';

        if (isEditMode) {
            url = `/api/payments/${currentEditId}`;
            method = 'POST'; // Laravel uses POST with _method for PUT in forms
            formData.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        });

        const data = await response.json();

        if (!response.ok) {
            if (data.errors) {
                showValidationErrors(data.errors);
                return;
            }
            throw new Error(data.message || 'Save failed');
        }

        showSuccess(data.message);
        hideForm();
        loadPayments();

    } catch (error) {
        console.error('Error saving payment:', error);
        showError('Failed to save payment method. Please try again.');
    } finally {
        elements.saveBtn.disabled = false;
        elements.saveBtn.textContent = isEditMode ? 'UPDATE' : 'SAVE';
    }
}

// Delete payment
async function deletePayment(methodId) {
    if (!confirm('Are you sure you want to delete this payment method?')) return;

    try {
        const response = await fetch(`/api/payments/${methodId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Delete failed');
        }

        showSuccess(data.message);
        loadPayments();

    } catch (error) {
        console.error('Error deleting payment:', error);
        showError('Failed to delete payment method. Please try again.');
    }
}

// Hide form
function hideForm() {
    elements.paymentForm.style.display = 'none';
    resetForm();
}

// Reset form
function resetForm() {
    elements.methodId.value = '';
    elements.methodName.value = '';
    elements.destinationAccount.value = '';
    elements.description.value = '';
    elements.imageUpload.value = '';
    elements.imagePreview.classList.remove('has-image');
    elements.previewImage.src = '';
    clearValidationErrors();
}

// Validate form
function validateForm() {
    clearValidationErrors();
    let isValid = true;

    if (!elements.methodName.value.trim()) {
        showFieldError('methodName', 'Method name is required');
        isValid = false;
    }

    return isValid;
}

// Handle image upload
function handleImageUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
        showError('Please select a valid image file.');
        event.target.value = '';
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showError('Image size must be less than 2MB.');
        event.target.value = '';
        return;
    }

    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        elements.previewImage.src = e.target.result;
        elements.imagePreview.classList.add('has-image');
    };
    reader.readAsDataURL(file);
}

// Remove image
function removeImage() {
    elements.imageUpload.value = '';
    elements.previewImage.src = '';
    elements.imagePreview.classList.remove('has-image');
}

// Show loading state
function showLoading() {
    elements.paymentItemsContainer.innerHTML = '<div class="no-data">Loading...</div>';
}

// Show error message
function showError(message) {
    // You can implement a toast notification system here
    alert('Error: ' + message);
}

// Show success message
function showSuccess(message) {
    // You can implement a toast notification system here
    alert('Success: ' + message);
}

// Show validation errors
function showValidationErrors(errors) {
    clearValidationErrors();
    
    Object.keys(errors).forEach(field => {
        const fieldElement = document.getElementById(field === 'method_name' ? 'methodName' : 
                                                  field === 'destination_account' ? 'destinationAccount' : field);
        if (fieldElement) {
            showFieldError(fieldElement.id, errors[field][0]);
        }
    });
}

// Show field error
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    field.style.borderColor = '#ff3333';
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.error-text');
    if (existingError) {
        existingError.remove();
    }

    // Add error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-text';
    errorDiv.style.color = '#ff3333';
    errorDiv.style.fontSize = '0.8rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Clear validation errors
function clearValidationErrors() {
    document.querySelectorAll('.error-text').forEach(el => el.remove());
    document.querySelectorAll('.form-control').forEach(el => {
        el.style.borderColor = '#ddd';
    });
}

// Utility function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}