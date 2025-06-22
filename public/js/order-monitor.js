// CSRF Token setup
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Auto refresh orders every 30 seconds
let refreshInterval;

document.addEventListener('DOMContentLoaded', function() {
    // Start auto refresh
    startAutoRefresh();
    
    // Modal functionality
    setupModal();
});

/**
 * Start auto refresh for orders
 */
function startAutoRefresh() {
    refreshInterval = setInterval(refreshOrders, 30000); // 30 seconds
}

/**
 * Stop auto refresh
 */
function stopAutoRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
}

/**
 * Refresh orders data
 */
async function refreshOrders() {
    try {
        const response = await fetch('/orders/latest', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const orders = await response.json();
        updateOrdersTable(orders);
        
        // Show success indicator
        showNotification('Orders refreshed successfully', 'success');
        
    } catch (error) {
        console.error('Error refreshing orders:', error);
        showNotification('Failed to refresh orders: ' + error.message, 'error');
    }
}

/**
 * Update orders table with new data
 */
function updateOrdersTable(orders) {
    const container = document.getElementById('ordersContainer');
    
    if (orders.length === 0) {
        container.innerHTML = '<div class="no-data">No orders found for today.</div>';
        return;
    }
    
    container.innerHTML = orders.map(order => {
        const canBeCancelled = ['pending', 'confirmed', 'preparing', 'ready'].includes(order.status);
        
        return `
            <div class="order-row" data-order-id="${order.order_id}">
                <div class="order-id">${order.order_id}</div>
                <div class="customer-name">${order.customer_name || 'N/A'}</div>
                <div class="customer-phone">${order.customer_phone || 'N/A'}</div>
                <div class="amount">Rp ${formatNumber(order.final_amount)}</div>
                <div class="status">
                    <span class="status-badge status-${getStatusColor(order.status)}">
                        ${getStatusLabel(order.status)}
                    </span>
                </div>
                <div class="time">${formatTime(order.created_at)}</div>
                <div class="actions">
                    <button onclick="viewOrder('${order.order_id}')" class="btn-view">View</button>
                    ${canBeCancelled ? generateStatusSelect(order.order_id, order.status) : '<span class="text-muted">Final</span>'}
                </div>
            </div>
        `;
    }).join('');
}

/**
 * Generate status select dropdown based on current status
 */
function generateStatusSelect(orderId, currentStatus) {
    const validTransitions = getValidStatusTransitions(currentStatus);
    
    let options = '<option value="">Change Status</option>';
    
    // Add current status as selected
    const currentLabel = getStatusLabel(currentStatus);
    options += `<option value="${currentStatus}" selected>Current: ${currentLabel}</option>`;
    
    // Add valid transitions
    validTransitions.forEach(status => {
        const label = getStatusLabel(status);
        options += `<option value="${status}">${label}</option>`;
    });
    
    return `
        <select onchange="updateOrderStatus('${orderId}', this.value)" class="status-select">
            ${options}
        </select>
    `;
}

/**
 * Get valid status transitions
 */
function getValidStatusTransitions(currentStatus) {
    const transitions = {
        'pending': ['confirmed', 'cancelled'],
        'confirmed': ['preparing', 'cancelled'],
        'preparing': ['ready', 'cancelled'],
        'ready': ['completed', 'cancelled'],
        'completed': [],
        'cancelled': []
    };
    
    return transitions[currentStatus] || [];
}

/**
 * Update order status - FIXED VERSION
 */
async function updateOrderStatus(orderId, status) {
    if (!status) return;
    
    // Don't update if it's the same status
    const currentRow = document.querySelector(`[data-order-id="${orderId}"]`);
    const currentStatusBadge = currentRow.querySelector('.status-badge');
    const currentStatus = getCurrentStatusFromBadge(currentStatusBadge);
    
    if (status === currentStatus) {
        // Reset the select to default
        const select = currentRow.querySelector('.status-select');
        if (select) select.value = '';
        return;
    }
    
    try {
        // Show loading state
        showNotification(`Updating order ${orderId} status...`, 'info');
        
        const response = await fetch(`/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            showNotification(`Order ${orderId} status updated to ${getStatusLabel(status)}`, 'success');
            
            // Update the specific row instead of refreshing entire table
            updateOrderRow(orderId, result.order);
            
        } else {
            throw new Error(result.message || `HTTP error! status: ${response.status}`);
        }
        
    } catch (error) {
        console.error('Error updating order status:', error);
        showNotification(`Failed to update order status: ${error.message}`, 'error');
        
        // Reset the select to previous value
        const select = currentRow.querySelector('.status-select');
        if (select) select.value = '';
    }
}

/**
 * Update specific order row with new data
 */
function updateOrderRow(orderId, orderData) {
    const orderRow = document.querySelector(`[data-order-id="${orderId}"]`);
    if (!orderRow) return;
    
    // Update status badge
    const statusContainer = orderRow.querySelector('.status');
    statusContainer.innerHTML = `
        <span class="status-badge status-${orderData.status_color}">
            ${orderData.status_label}
        </span>
    `;
    
    // Update actions (status select)
    const actionsContainer = orderRow.querySelector('.actions');
    const viewButton = actionsContainer.querySelector('.btn-view').outerHTML;
    
    const canBeCancelled = ['pending', 'confirmed', 'preparing', 'ready'].includes(orderData.status);
    
    actionsContainer.innerHTML = viewButton + 
        (canBeCancelled ? 
            generateStatusSelect(orderId, orderData.status) : 
            '<span class="text-muted">Final</span>'
        );
}

/**
 * Get current status from status badge
 */
function getCurrentStatusFromBadge(statusBadge) {
    const classList = Array.from(statusBadge.classList);
    const statusClass = classList.find(cls => cls.startsWith('status-'));
    
    if (statusClass) {
        const statusColor = statusClass.replace('status-', '');
        // Reverse lookup from color to status
        const colorToStatus = {
            'warning': 'pending',
            'info': 'confirmed',
            'primary': 'preparing',
            'success': 'ready', // Note: both ready and completed use success
            'danger': 'cancelled'
        };
        
        // For success, we need to check the text content
        if (statusColor === 'success') {
            const text = statusBadge.textContent.trim();
            return text === 'Siap Diambil' ? 'ready' : 'completed';
        }
        
        return colorToStatus[statusColor] || 'unknown';
    }
    
    return 'unknown';
}

/**
 * View order details
 */
async function viewOrder(orderId) {
    try {
        showNotification('Loading order details...', 'info');
        
        const response = await fetch(`/orders/${orderId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'text/html'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const html = await response.text();
        
        // Show modal with order details
        showOrderModal(html);
        
    } catch (error) {
        console.error('Error fetching order details:', error);
        showNotification('Failed to load order details: ' + error.message, 'error');
    }
}

/**
 * Load weekly stats
 */
async function loadWeeklyStats() {
    try {
        showNotification('Loading weekly statistics...', 'info');
        
        const response = await fetch('/orders/weekly-stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const stats = await response.json();
        showWeeklyStatsModal(stats);
        
    } catch (error) {
        console.error('Error fetching weekly stats:', error);
        showNotification('Failed to load weekly stats: ' + error.message, 'error');
    }
}

/**
 * Show order modal
 */
function showOrderModal(content) {
    const modal = document.getElementById('orderModal');
    const modalDetails = document.getElementById('orderDetails');
    
    modalDetails.innerHTML = content;
    modal.style.display = 'block';
}

/**
 * Show weekly stats modal
 */
function showWeeklyStatsModal(stats) {
    const modal = document.getElementById('orderModal');
    const modalDetails = document.getElementById('orderDetails');
    
    const content = `
        <h3>Weekly Statistics</h3>
        <div style="margin: 1rem 0;">
            <p><strong>Weekly Revenue:</strong> Rp ${formatNumber(stats.weekly_revenue)}</p>
            <p><strong>Weekly Orders:</strong> ${stats.weekly_orders}</p>
        </div>
        <h4>Daily Breakdown:</h4>
        <div style="margin-top: 1rem;">
            ${stats.daily_breakdown.map(day => `
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #eee;">
                    <span>${formatDate(day.date)}</span>
                    <span>Rp ${formatNumber(day.revenue)} (${day.orders} orders)</span>
                </div>
            `).join('')}
        </div>
    `;
    
    modalDetails.innerHTML = content;
    modal.style.display = 'block';
}

/**
 * Setup modal functionality
 */
function setupModal() {
    const modal = document.getElementById('orderModal');
    const closeBtn = modal.querySelector('.close');
    
    // Close modal on X click
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    };
    
    // Close modal on outside click
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
}

/**
 * Show notification with better styling and positioning
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Style the notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 350px;
        word-wrap: break-word;
    `;
    
    // Set background color based on type
    switch (type) {
        case 'success':
            notification.style.backgroundColor = '#27ae60';
            break;
        case 'error':
            notification.style.backgroundColor = '#e74c3c';
            break;
        case 'warning':
            notification.style.backgroundColor = '#f39c12';
            break;
        default:
            notification.style.backgroundColor = '#3498db';
    }
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after appropriate time (longer for errors)
    const duration = type === 'error' ? 5000 : 3000;
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, duration);
}

/**
 * Utility functions
 */
function formatNumber(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        weekday: 'short',
        month: 'short',
        day: 'numeric'
    });
}

function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'confirmed': 'info',
        'preparing': 'primary',
        'ready': 'success',
        'completed': 'success',
        'cancelled': 'danger'
    };
    return colors[status] || 'secondary';
}

function getStatusLabel(status) {
    const labels = {
        'pending': 'Menunggu Konfirmasi',
        'confirmed': 'Dikonfirmasi',
        'preparing': 'Sedang Diproses',
        'ready': 'Siap Diambil',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
    };
    return labels[status] || 'Unknown';
}

// Clean up interval when page unloads
window.addEventListener('beforeunload', function() {
    stopAutoRefresh();
});