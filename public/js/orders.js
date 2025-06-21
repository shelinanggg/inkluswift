// Orders.js - Order Management System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeOrderManagement();
});

function initializeOrderManagement() {
    // Initialize event listeners
    setupBulkActions();
    setupFilters();
    setupAlerts();
    setupModals();
    
    console.log('Order Management System Initialized');
}

// Bulk Actions Management
function setupBulkActions() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const cancelBulkBtn = document.getElementById('cancelBulk');
    const bulkForm = document.getElementById('bulkForm');

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Individual checkbox listeners
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            
            // Update select all checkbox state
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedBoxes.length === orderCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < orderCheckboxes.length;
            }
        });
    });

    // Cancel bulk action
    if (cancelBulkBtn) {
        cancelBulkBtn.addEventListener('click', function() {
            clearBulkSelection();
        });
    }

    // Bulk form submission
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                showAlert('Please select at least one order', 'error');
                return;
            }

            if (!confirm(`Are you sure you want to update ${checkedBoxes.length} order(s)?`)) {
                e.preventDefault();
                return;
            }

            // Add selected order IDs to form
            const orderIds = Array.from(checkedBoxes).map(cb => cb.value);
            orderIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'order_ids[]';
                input.value = id;
                this.appendChild(input);
            });
        });
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkedBoxes.length;

        if (count > 0) {
            bulkActions.style.display = 'block';
            selectedCount.textContent = `${count} selected`;
        } else {
            bulkActions.style.display = 'none';
        }
    }

    function clearBulkSelection() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
        bulkActions.style.display = 'none';
    }
}

// Filter Management
function setupFilters() {
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');

    // Auto-submit on filter changes
    [statusFilter, dateFrom, dateTo].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    });

    // Search with debounce
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }

    // Date validation
    if (dateFrom && dateTo) {
        dateFrom.addEventListener('change', function() {
            dateTo.min = this.value;
            if (dateTo.value && dateTo.value < this.value) {
                dateTo.value = this.value;
            }
        });

        dateTo.addEventListener('change', function() {
            dateFrom.max = this.value;
            if (dateFrom.value && dateFrom.value > this.value) {
                dateFrom.value = this.value;
            }
        });
    }
}

// Modal Management
function setupModals() {
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target.id);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal[style*="block"]');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
}

// View Order
function viewOrder(orderId) {
    window.location.href = `/admin/orders/${orderId}`;
}

// Status Modal
function showStatusModal(orderId, currentStatus) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const orderIdInput = document.getElementById('modalOrderId');
    const statusSelect = document.getElementById('modalStatus');

    orderIdInput.value = orderId;
    statusSelect.value = currentStatus;
    form.action = `/admin/orders/${orderId}/status`;

    // Filter valid status options based on current status
    filterStatusOptions(statusSelect, currentStatus);

    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('show'), 10);
}

function filterStatusOptions(selectElement, currentStatus) {
    const validTransitions = {
        'pending': ['confirmed', 'cancelled'],
        'confirmed': ['preparing', 'cancelled'],
        'preparing': ['ready', 'cancelled'],
        'ready': ['completed', 'cancelled'],
        'completed': [],
        'cancelled': []
    };

    const validStatuses = validTransitions[currentStatus] || [];
    const options = selectElement.querySelectorAll('option');

    options.forEach(option => {
        if (option.value === currentStatus || validStatuses.includes(option.value)) {
            option.style.display = 'block';
            option.disabled = false;
        } else {
            option.style.display = 'none';
            option.disabled = true;
        }
    });
}

// Cancel Modal
function showCancelModal(orderId) {
    const modal = document.getElementById('cancelModal');
    const form = document.getElementById('cancelForm');
    const orderIdInput = document.getElementById('cancelOrderId');

    orderIdInput.value = orderId;
    form.action = `/admin/orders/${orderId}/cancel`;

    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Export Modal
function showExportModal() {
    const modal = document.getElementById('exportModal');
    const dateFromInput = modal.querySelector('input[name="date_from"]');
    const dateToInput = modal.querySelector('input[name="date_to"]');

    // Set default dates (last 30 days)
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);

    dateFromInput.value = thirtyDaysAgo.toISOString().split('T')[0];
    dateToInput.value = today.toISOString().split('T')[0];

    modal.style.display = 'block';
    setTimeout(() => modal.classList.add('show'), 10);
}

// Close Modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }, 300);
}

// Alert Management
function setupAlerts() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            hideAlert(alert);
        }, 5000);

        // Add click to dismiss
        alert.addEventListener('click', () => hideAlert(alert));
    });
}

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '10000';

    document.body.appendChild(alertDiv);

    setTimeout(() => alertDiv.classList.add('show'), 10);
    setTimeout(() => hideAlert(alertDiv), 5000);

    alertDiv.addEventListener('click', () => hideAlert(alertDiv));
}

function hideAlert(alert) {
    alert.classList.add('hide');
    setTimeout(() => {
        if (alert.parentNode) {
            alert.parentNode.removeChild(alert);
        }
    }, 300);
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Real-time Updates (Optional - for future implementation)
function setupRealTimeUpdates() {
    // This could be implemented with WebSockets or Server-Sent Events
    // For now, we'll use periodic polling
    setInterval(() => {
        checkForOrderUpdates();
    }, 30000); // Check every 30 seconds
}

function checkForOrderUpdates() {
    // This would make an AJAX call to check for new orders
    // Implementation would depend on your backend API
    console.log('Checking for order updates...');
}

// Keyboard Shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + F for search focus
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    // Escape to clear search
    if (e.key === 'Escape') {
        const searchInput = document.getElementById('searchInput');
        if (searchInput && document.activeElement === searchInput) {
            searchInput.value = '';
            document.getElementById('filterForm').submit();
        }
    }
});

// Export functionality
function exportOrders(format = 'csv') {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '/admin/orders/export';

    // Get current filter values
    const filters = {
        date_from: document.getElementById('dateFrom')?.value || '',
        date_to: document.getElementById('dateTo')?.value || '',
        status: document.getElementById('statusFilter')?.value || 'all',
        search: document.getElementById('searchInput')?.value || ''
    };

    Object.keys(filters).forEach(key => {
        if (filters[key]) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = filters[key];
            form.appendChild(input);
        }
    });

    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

// Performance optimization
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

// Initialize tooltips (if you want to add them)
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(e) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = e.target.getAttribute('data-tooltip');
    document.body.appendChild(tooltip);

    const rect = e.target.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
}

function hideTooltip() {
    const tooltip = document.querySelector('.tooltip');
    if (tooltip) {
        tooltip.remove();
    }
}