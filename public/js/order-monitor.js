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
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch orders');
        }
        
        const orders = await response.json();
        updateOrdersTable(orders);
        
        // Show success indicator
        showNotification('Orders refreshed successfully', 'success');
        
    } catch (error) {
        console.error('Error refreshing orders:', error);
        showNotification('Failed to refresh orders', 'error');
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
        const canBeCancelled = ['pending', 'confirmed'].includes(order.status);
        
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
                    ${canBeCancelled ? `
                        <select onchange="updateOrderStatus('${order.order_id}', this.value)" class="status-select">
                            <option value="">Change Status</option>
                            <option value="confirmed" ${order.status === 'confirmed' ? 'selected' : ''}>Confirm</option>
                            <option value="preparing" ${order.status === 'preparing' ? 'selected' : ''}>Preparing</option>
                            <option value="ready" ${order.status === 'ready' ? 'selected' : ''}>Ready</option>
                            <option value="completed" ${order.status === 'completed' ? 'selected' : ''}>Complete</option>
                            <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancel</option>
                        </select>
                    ` : ''}
                </div>
            </div>
        `;
    }).join('');
}

/**
 * Update order status
 */
async function updateOrderStatus(orderId, status) {
    if (!status) return;
    
    try {
        const response = await fetch(`/orders/${orderId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ status: status })
        });
        
        if (!response.ok) {
            throw new Error('Failed to update order status');
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(`Order ${orderId} status updated to ${status}`, 'success');
            refreshOrders(); // Refresh the table
        } else {
            throw new Error(result.message || 'Failed to update status');
        }
        
    } catch (error) {
        console.error('Error updating order status:', error);
        showNotification('Failed to update order status', 'error');
    }
}

/**
 * View order details
 */
async function viewOrder(orderId) {
    try {
        const response = await fetch(`/orders/${orderId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch order details');
        }
        
        const html = await response.text();
        
        // Show modal with order details
        showOrderModal(html);
        
    } catch (error) {
        console.error('Error fetching order details:', error);
        showNotification('Failed to load order details', 'error');
    }
}

/**
 * Load weekly stats
 */
async function loadWeeklyStats() {
    try {
        const response = await fetch('/orders/weekly-stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to fetch weekly stats');
        }
        
        const stats = await response.json();
        showWeeklyStatsModal(stats);
        
    } catch (error) {
        console.error('Error fetching weekly stats:', error);
        showNotification('Failed to load weekly stats', 'error');
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
 * Show notification
 */
function showNotification(message, type = 'info') {
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
        border-radius: 5px;
        color: white;
        font-weight: bold;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
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
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
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