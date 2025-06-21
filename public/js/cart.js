// Cart JavaScript Functions - Optimized Version
document.addEventListener('DOMContentLoaded', function() {
    // Setup CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    // Initialize cart functionality
    initializeCart();
});

/**
 * Initialize cart functionality on page load
 */
function initializeCart() {
    // Update cart count on page load
    updateCartCount();
    
    // Add event listeners for quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('blur', function() {
            const cartId = this.id.replace('quantity-', '');
            updateQuantityInput(cartId);
        });
        
        // Handle Enter key press
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.blur();
            }
        });
        
        // Prevent invalid input
        input.addEventListener('input', function() {
            if (parseInt(this.value) < 1 || isNaN(parseInt(this.value))) {
                this.value = 1;
            }
        });
    });
    
    // Auto-hide existing alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            fadeOutAlert(alert);
        });
    }, 5000);
}

/**
 * Show loading overlay
 */
function showLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    const loadingOverlay = document.getElementById('loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
}

/**
 * Show alert message with consistent parameter order
 */
function showAlert(message, type = 'success') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    
    // Add icon and message
    const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
    alertDiv.innerHTML = `
        <i class="fas fa-${icon}"></i>
        <span>${message}</span>
    `;
    
    // Insert alert at the top of cart container
    const cartContainer = document.querySelector('.cart-container');
    if (cartContainer) {
        cartContainer.insertBefore(alertDiv, cartContainer.firstChild);
    } else {
        // Fallback: insert at top of body
        document.body.insertBefore(alertDiv, document.body.firstChild);
    }
    
    // Auto remove alert after 5 seconds
    setTimeout(() => {
        fadeOutAlert(alertDiv);
    }, 5000);
    
    // Scroll to top to show alert
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

/**
 * Fade out alert with animation
 */
function fadeOutAlert(alert) {
    if (alert && alert.parentNode) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        alert.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 300);
    }
}

/**
 * Update cart count in header
 */
function updateCartCount() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }
    
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = data.count;
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

/**
 * Update quantity via plus/minus buttons
 */
function updateQuantity(cartId, action) {
    const quantityInput = document.getElementById(`quantity-${cartId}`);
    if (!quantityInput) {
        console.error(`Quantity input not found for cart ID: ${cartId}`);
        return;
    }
    
    const currentQuantity = parseInt(quantityInput.value) || 1;
    let newQuantity = currentQuantity;
    
    if (action === 'plus') {
        newQuantity = currentQuantity + 1;
    } else if (action === 'minus') {
        if (currentQuantity > 1) {
            newQuantity = currentQuantity - 1;
        } else {
            // If quantity is 1 and user clicks minus, ask for confirmation to remove
            if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                removeItem(cartId);
                return;
            } else {
                return;
            }
        }
    }
    
    quantityInput.value = newQuantity;
    debouncedUpdateQuantity(cartId, newQuantity);
}

/**
 * Update quantity via direct input
 */
function updateQuantityInput(cartId) {
    const quantityInput = document.getElementById(`quantity-${cartId}`);
    if (!quantityInput) {
        console.error(`Quantity input not found for cart ID: ${cartId}`);
        return;
    }
    
    let newQuantity = parseInt(quantityInput.value);
    
    if (newQuantity < 1 || isNaN(newQuantity)) {
        quantityInput.value = 1;
        newQuantity = 1;
    }
    
    debouncedUpdateQuantity(cartId, newQuantity);
}

/**
 * Send quantity update request to server
 */
function updateQuantityRequest(cartId, quantity) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    showLoading();
    
    fetch('/cart/update-quantity', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            // Update subtotal for this item
            const subtotalElement = document.getElementById(`subtotal-${cartId}`);
            if (subtotalElement) {
                subtotalElement.textContent = `Rp ${formatNumber(data.subtotal)}`;
            }
            
            // Recalculate totals
            recalculateTotals();
            updateCartCount();
            
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
            // Reset quantity input to previous value
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengupdate quantity', 'error');
        setTimeout(() => location.reload(), 1000);
    });
}

/**
 * Remove item from cart
 */
function removeItem(cartId) {
    if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    showLoading();
    
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            cart_id: cartId
        })
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (cartItem) {
                cartItem.style.opacity = '0';
                cartItem.style.transform = 'translateX(-100%)';
                cartItem.style.transition = 'all 0.3s ease';
                setTimeout(() => cartItem.remove(), 300);
            }
            
            // Check if cart is empty after animation
            setTimeout(() => {
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    location.reload(); // Reload to show empty cart message
                } else {
                    recalculateTotals();
                    updateCartCount();
                }
            }, 350);
            
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus item', 'error');
    });
}

/**
 * Clear all items from cart
 */
function clearAllCart() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    showLoading();
    
    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showAlert(data.message, 'success');
            // Reload page to show empty cart
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengosongkan keranjang', 'error');
    });
}

/**
 * Proceed to checkout
 */
function proceedToCheckout() {
    const cartItems = document.querySelectorAll('.cart-item');
    if (cartItems.length === 0) {
        showAlert('Keranjang belanja kosong', 'error');
        return;
    }
    
    // Add loading state
    showLoading();
    
    // Redirect to checkout page
    window.location.href = '/checkout';
}

/**
 * Recalculate totals after quantity changes
 */
function recalculateTotals() {
    let totalItems = 0;
    let totalAmount = 0;
    
    // Calculate totals from all cart items
    document.querySelectorAll('.cart-item').forEach(item => {
        const cartId = item.getAttribute('data-cart-id');
        const quantityInput = document.getElementById(`quantity-${cartId}`);
        const subtotalElement = document.getElementById(`subtotal-${cartId}`);
        
        if (quantityInput && subtotalElement) {
            const quantity = parseInt(quantityInput.value) || 0;
            const subtotalText = subtotalElement.textContent.replace(/[^\d]/g, '');
            const subtotal = parseInt(subtotalText) || 0;
            
            totalItems += quantity;
            totalAmount += subtotal;
        }
    });
    
    // Update total displays
    const totalItemsElement = document.getElementById('total-items');
    const totalAmountElement = document.getElementById('total-amount');
    
    if (totalItemsElement) {
        totalItemsElement.textContent = `${totalItems} item${totalItems > 1 ? 's' : ''}`;
    }
    
    if (totalAmountElement) {
        totalAmountElement.textContent = `Rp ${formatNumber(totalAmount)}`;
    }
}

/**
 * Format number with thousand separators
 */
function formatNumber(num) {
    if (isNaN(num)) return '0';
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Add item to cart from description page
 */
function addToCartFromDescription(menuId, quantity = 1) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        showAlert('Token keamanan tidak ditemukan', 'error');
        return;
    }
    
    showLoading();
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            menu_id: menuId,
            quantity: quantity
        })
    })
    .then(response => {
        if (response.status === 401) {
            // Redirect to login if not authenticated
            window.location.href = '/login';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (!data) return; // Handle the case where we redirected to login
        
        hideLoading();
        
        if (data.success) {
            showAlert(data.message, 'success');
            updateCartCount();
            
            // Optional: Show success animation or redirect
            // setTimeout(() => {
            //     window.location.href = '/cart';
            // }, 1500);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menambahkan item ke keranjang', 'error');
    });
}

/**
 * Handle keyboard navigation for accessibility
 */
document.addEventListener('keydown', function(e) {
    // Handle Escape key to close loading overlay
    if (e.key === 'Escape') {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay && loadingOverlay.style.display === 'flex') {
            hideLoading();
        }
    }
});

/**
 * Utility function to debounce API calls
 */
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

// Debounced version of quantity update for better performance
const debouncedUpdateQuantity = debounce(updateQuantityRequest, 500);