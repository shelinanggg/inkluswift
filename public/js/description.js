/**
 * Toggle visibility of info sections
 * @param {string} id - The section ID to toggle
 */
function toggleSection(id) {
    const content = document.getElementById(`${id}-content`);
    const chevron = document.getElementById(`${id}-chevron`);
    
    if (content && content.classList.contains('hidden')) {
        // Show content
        content.classList.remove('hidden');
        if (chevron) {
            chevron.classList.remove('down');
            chevron.classList.add('up');
        }
    } else if (content) {
        // Hide content
        content.classList.add('hidden');
        if (chevron) {
            chevron.classList.remove('up');
            chevron.classList.add('down');
        }
    }
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
 * Show alert message (improved version)
 */
function showAlert(message, type = 'success') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Add unique ID
    alertDiv.id = `alert-${Date.now()}`;
    
    // Insert after nav
    const nav = document.querySelector('nav');
    if (nav) {
        nav.insertAdjacentElement('afterend', alertDiv);
    } else {
        // Fallback: insert at top of body
        document.body.insertBefore(alertDiv, document.body.firstChild);
    }
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentElement) {
            alertDiv.style.opacity = '0';
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 5000);
}

/**
 * Show notification message (floating notification style)
 */
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">
                ${type === 'success' ? '✓' : '⚠'}
            </span>
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;

    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
        color: ${type === 'success' ? '#155724' : '#721c24'};
        border: 1px solid ${type === 'success' ? '#c3e6cb' : '#f5c6cb'};
        border-radius: 8px;
        padding: 15px 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    `;

    // Add to page
    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 3000);
}

/**
 * Increase quantity
 */
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue < 99) {
            quantityInput.value = currentValue + 1;
        }
    }
}

/**
 * Decrease quantity
 */
function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        const currentValue = parseInt(quantityInput.value) || 1;
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }
}

/**
 * Add item to cart (improved version with proper error handling)
 */
function addToCart(menuId, quantity = null) {
    const quantityInput = document.getElementById('quantity');
    const finalQuantity = quantity || (quantityInput ? parseInt(quantityInput.value) : 1);
    
    if (finalQuantity < 1) {
        showAlert('Jumlah harus minimal 1', 'error');
        return;
    }
    
    if (finalQuantity > 99) {
        showAlert('Jumlah maksimal 99', 'error');
        return;
    }
    
    // Show loading
    showLoading();
    
    // Disable add to cart button
    const addButton = document.querySelector('.add-to-cart');
    const originalButtonText = addButton ? addButton.innerHTML : '';
    if (addButton) {
        addButton.disabled = true;
        addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
    }
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            menu_id: menuId,
            quantity: finalQuantity
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        hideLoading();
        
        if (data.success) {
            showNotification(data.message || 'Item berhasil ditambahkan ke keranjang!', 'success');
            
            // Update cart count
            updateCartCount();
            
            // Reset quantity to 1
            if (quantityInput) {
                quantityInput.value = 1;
            }
            
            // Show success animation on button
            if (addButton) {
                addButton.style.background = '#28a745';
                addButton.innerHTML = '<i class="fas fa-check"></i> Berhasil Ditambahkan!';
                
                setTimeout(() => {
                    addButton.style.background = '';
                    addButton.innerHTML = originalButtonText;
                }, 2000);
            }
            
        } else {
            showAlert(data.message || 'Gagal menambahkan item ke keranjang', 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        
        if (error.message.includes('401')) {
            showAlert('Silakan login terlebih dahulu', 'error');
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            showAlert('Terjadi kesalahan saat menambahkan item ke keranjang', 'error');
        }
    })
    .finally(() => {
        // Re-enable button
        if (addButton) {
            addButton.disabled = false;
            if (addButton.innerHTML.includes('Menambahkan...')) {
                addButton.innerHTML = originalButtonText;
            }
        }
    });
}

/**
 * Update cart count in header
 */
function updateCartCount() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    fetch('/cart/count', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCartCountDisplay(data.count || 0);
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

/**
 * Update cart count display
 */
function updateCartCountDisplay(count) {
    const cartCountElement = document.getElementById('cartCount') || 
                            document.querySelector('.auth-buttons a[href*="cart"] span') ||
                            document.querySelector('.cart-count');
    
    if (cartCountElement) {
        cartCountElement.textContent = count;
        
        // Add animation
        cartCountElement.style.transform = 'scale(1.2)';
        cartCountElement.style.color = '#e53e3e';
        
        setTimeout(() => {
            cartCountElement.style.transform = 'scale(1)';
            cartCountElement.style.color = '';
        }, 300);
        
        // Show/hide cart count badge
        if (count > 0) {
            cartCountElement.style.display = 'inline-block';
        } else {
            cartCountElement.style.display = 'none';
        }
    }
}

/**
 * Close alert manually
 */
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    }
}

/**
 * Cart page specific functions
 */

// Increase quantity (for cart page)
function increaseCartQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    if (input) {
        const currentValue = parseInt(input.value) || 1;
        if (currentValue < 99) {
            input.value = currentValue + 1;
            updateCartQuantity(itemId);
        }
    }
}

// Decrease quantity (for cart page)
function decreaseCartQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    if (input) {
        const currentValue = parseInt(input.value) || 1;
        if (currentValue > 1) {
            input.value = currentValue - 1;
            updateCartQuantity(itemId);
        }
    }
}

// Update quantity via AJAX (for cart page)
function updateCartQuantity(itemId) {
    const input = document.getElementById(`quantity-${itemId}`);
    const form = input.closest('.quantity-form');
    
    if (form) {
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateItemTotal(itemId, data.itemTotal);
                updateCartTotals();
                showNotification(data.message || 'Kuantitas berhasil diperbarui', 'success');
            } else {
                showAlert(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            form.submit(); // Fallback to regular form submission
        });
    }
}

// Update item total display
function updateItemTotal(itemId, newTotal) {
    const itemRow = document.querySelector(`[data-item-id="${itemId}"]`);
    if (itemRow) {
        const totalElement = itemRow.querySelector('.total-price');
        if (totalElement) {
            totalElement.textContent = `Rp ${formatNumber(newTotal)}`;
        }
    }
}

// Update cart totals
function updateCartTotals() {
    const cartItems = document.querySelectorAll('.cart-item');
    let totalItems = 0;
    let totalAmount = 0;
    
    cartItems.forEach(item => {
        const quantityInput = item.querySelector('.quantity-input');
        const priceElement = item.querySelector('.item-price');
        
        if (quantityInput && priceElement) {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceElement.textContent.replace(/[^\d]/g, '')) || 0;
            
            totalItems += quantity;
            totalAmount += quantity * price;
        }
    });
    
    // Update summary
    const summaryRow = document.querySelector('.summary-row:not(.total)');
    if (summaryRow) {
        const itemCountSpan = summaryRow.querySelector('span:first-child');
        const subtotalSpan = summaryRow.querySelector('span:last-child');
        
        if (itemCountSpan) {
            itemCountSpan.textContent = `Subtotal (${totalItems} item)`;
        }
        
        if (subtotalSpan) {
            subtotalSpan.textContent = `Rp ${formatNumber(totalAmount)}`;
        }
    }
    
    // Update total
    const totalRow = document.querySelector('.summary-row.total');
    if (totalRow) {
        const totalSpan = totalRow.querySelector('span:last-child');
        if (totalSpan) {
            totalSpan.textContent = `Rp ${formatNumber(totalAmount)}`;
        }
    }
}

// Format number with thousand separators
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Confirm delete item
function confirmDelete() {
    return confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?');
}

// Confirm clear cart
function confirmClearCart() {
    return confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?');
}

/**
 * Initialize the page when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    // Update cart count on page load
    updateCartCount();
    
    // Add smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Handle product image loading
    const productImage = document.querySelector('.product-image img');
    if (productImage) {
        if (productImage.complete && productImage.naturalWidth > 0) {
            productImage.style.opacity = '1';
        } else {
            productImage.style.opacity = '0';
            
            productImage.addEventListener('load', function() {
                this.style.opacity = '1';
            });
        }
        
        productImage.addEventListener('error', function() {
            this.src = '/Assets/default-food.png';
            this.alt = 'Gambar tidak tersedia';
            this.style.opacity = '1';
        });
    }

    // Add hover effects for interactive elements
    const infoHeaders = document.querySelectorAll('.info-header');
    infoHeaders.forEach(header => {
        header.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        header.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Add keyboard navigation support
    infoHeaders.forEach(header => {
        header.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        if (!header.hasAttribute('tabindex')) {
            header.setAttribute('tabindex', '0');
        }
    });

    // Enhanced form validation for add to cart - REMOVED ERROR MESSAGES
    const addToCartForms = document.querySelectorAll('form[action*="cart"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const menuId = this.querySelector('input[name="menu_id"]')?.value;
            const quantity = this.querySelector('input[name="quantity"]')?.value || 1;
            
            if (menuId) {
                addToCart(menuId, quantity);
            }
            // Removed error message - just silently fail if no menuId
        });
    });

    // Handle direct button clicks - REMOVED ERROR MESSAGES
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        if (!button.closest('form')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const menuId = this.dataset.menuId || this.getAttribute('data-menu-id');
                const quantity = this.dataset.quantity || 1;
                
                if (menuId) {
                    addToCart(menuId, quantity);
                }
                // Removed error message - just silently fail if no menuId
            });
        }
    });

    // Add validation for quantity input
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > 99) {
                this.value = 99;
            }
        });
        
        // Handle keyboard events
        quantityInput.addEventListener('keypress', function(e) {
            // Only allow numbers and control keys
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
            
            // Handle Enter key to add to cart
            if (e.key === 'Enter') {
                const menuId = document.querySelector('input[name="menu_id"]')?.value ||
                             document.querySelector('[data-menu-id]')?.getAttribute('data-menu-id');
                if (menuId) {
                    addToCart(menuId);
                }
            }
        });
    }

    // Handle URL parameters for cart success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('cart_added') === 'true') {
        showNotification('Item berhasil ditambahkan ke keranjang!', 'success');
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }

    // Handle flash messages from server
    const successMessage = document.querySelector('.alert-success');
    const errorMessage = document.querySelector('.alert-error');
    
    if (successMessage) {
        showNotification(successMessage.textContent.trim(), 'success');
        successMessage.remove();
    }
    
    if (errorMessage) {
        showNotification(errorMessage.textContent.trim(), 'error');
        errorMessage.remove();
    }

    // Update cart totals if on cart page
    if (document.querySelector('.cart-content')) {
        updateCartTotals();
    }

    // Initialize tooltips for accessibility
    if (quantityInput) {
        quantityInput.setAttribute('title', 'Gunakan tombol + / - atau ketik langsung. Tekan Enter untuk menambah ke keranjang.');
    }
    
    const addToCartBtn = document.querySelector('.add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.setAttribute('title', 'Klik untuk menambahkan item ke keranjang belanja');
    }
});

// Handle form submissions with loading states
document.addEventListener('submit', function(e) {
    const form = e.target;
    
    if (form.classList.contains('quantity-form')) {
        e.preventDefault();
        const itemId = form.querySelector('.quantity-input').id.split('-')[1];
        updateCartQuantity(itemId);
        return;
    }
    
    if (form.classList.contains('delete-form') || form.classList.contains('clear-cart-form')) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }
    }
});

// Handle cart page quantity input validation
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        const input = e.target;
        let value = parseInt(input.value);
        
        if (isNaN(value) || value < 1) {
            input.value = 1;
        } else if (value > 99) {
            input.value = 99;
        }
        
        const itemId = input.id.split('-')[1];
        clearTimeout(input.updateTimeout);
        input.updateTimeout = setTimeout(() => {
            updateCartQuantity(itemId);
        }, 1000);
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Allow + and - keys to change quantity when quantity input is focused
    if (e.target.id === 'quantity') {
        if (e.key === '+' || e.key === '=') {
            e.preventDefault();
            increaseQuantity();
        } else if (e.key === '-') {
            e.preventDefault();
            decreaseQuantity();
        }
    }
    
    // Allow Escape key to close alerts
    if (e.key === 'Escape') {
        const alerts = document.querySelectorAll('.alert, .notification');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }
});

// Prevent double-click on buttons
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('quantity-btn') || e.target.classList.contains('add-to-cart')) {
        if (e.target.disabled) {
            e.preventDefault();
            return false;
        }
        
        e.target.disabled = true;
        setTimeout(() => {
            e.target.disabled = false;
        }, 300);
    }
});

// Handle responsive behavior
function handleResize() {
    const cartContent = document.querySelector('.cart-content');
    if (cartContent && window.innerWidth <= 768) {
        cartContent.style.gridTemplateColumns = '1fr';
    } else if (cartContent) {
        cartContent.style.gridTemplateColumns = '1fr 350px';
    }
}

window.addEventListener('resize', handleResize);
handleResize(); // Initial call

// Add CSS animations via JavaScript
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .product-image img {
        transition: opacity 0.3s ease;
    }
    
    .info-header {
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    
    .add-to-cart {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .add-to-cart:active {
        transform: translateY(0);
    }
    
    .add-to-cart:disabled {
        transform: none !important;
        cursor: not-allowed;
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .notification-icon {
        font-weight: bold;
        font-size: 16px;
    }
    
    .notification-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
        color: inherit;
    }
    
    .notification-close:hover {
        opacity: 0.7;
    }
    
    .alert {
        position: relative;
        padding: 12px 20px;
        margin: 10px 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: opacity 0.3s ease;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-close {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        margin-left: auto;
        color: inherit;
        padding: 0;
    }
    
    .alert-close:hover {
        opacity: 0.7;
    }
`;
document.head.appendChild(style);

// Make functions globally available
window.toggleSection = toggleSection;
window.addToCart = addToCart;
window.showNotification = showNotification;
window.showAlert = showAlert;
window.showLoading = showLoading;
window.hideLoading = hideLoading;
window.closeAlert = closeAlert;
window.increaseQuantity = increaseQuantity;
window.decreaseQuantity = decreaseQuantity;
window.increaseCartQuantity = increaseCartQuantity;
window.decreaseCartQuantity = decreaseCartQuantity;
window.updateCartCount = updateCartCount;
window.updateCartCountDisplay = updateCartCountDisplay;
window.updateCartQuantity = updateCartQuantity;
window.updateCartTotals = updateCartTotals;
window.confirmDelete = confirmDelete;
window.confirmClearCart = confirmClearCart;