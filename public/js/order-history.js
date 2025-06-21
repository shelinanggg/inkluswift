// Order History JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Confirm cancellation
    const cancelForms = document.querySelectorAll('.cancel-form');
    cancelForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
                e.preventDefault();
            }
        });
    });

    // Filter change handler
    const filterSelect = document.querySelector('.filter-select');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            // Show loading state
            this.style.opacity = '0.6';
            this.disabled = true;
            
            // Submit form after brief delay for better UX
            setTimeout(() => {
                this.form.submit();
            }, 200);
        });
    }

    // Reorder confirmation
    const reorderForms = document.querySelectorAll('form[action*="reorder"]');
    reorderForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = form.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            button.disabled = true;
        });
    });

    // Order card hover effects
    const orderCards = document.querySelectorAll('.order-card');
    orderCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Smooth scroll to top after actions
    if (window.location.hash === '#top') {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Print functionality
    const printButton = document.querySelector('.btn-print');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Hide non-printable elements
            const elementsToHide = document.querySelectorAll('.auth-buttons, .order-actions, .order-actions-detail, .filter-section');
            elementsToHide.forEach(el => {
                el.style.display = 'none';
            });

            // Print
            window.print();

            // Restore elements after print
            setTimeout(() => {
                elementsToHide.forEach(el => {
                    el.style.display = '';
                });
            }, 1000);
        });
    }

    // Keyboard navigation for accessibility
    document.addEventListener('keydown', function(e) {
        // Press 'f' to focus on filter
        if (e.key === 'f' && !e.ctrlKey && !e.altKey && filterSelect) {
            e.preventDefault();
            filterSelect.focus();
        }

        // Press 'p' to print (when on detail page)
        if (e.key === 'p' && !e.ctrlKey && !e.altKey && printButton) {
            e.preventDefault();
            printButton.click();
        }

        // Press 'Escape' to close any modal-like elements
        if (e.key === 'Escape') {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }
    });

    // Lazy loading for order images (if any)
    const images = document.querySelectorAll('img[data-src]');
    if (images.length > 0) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Status refresh functionality (for real-time updates)
    function refreshOrderStatus() {
        const orderCards = document.querySelectorAll('.order-card');
        const detailPage = document.querySelector('.order-detail');
        
        if (orderCards.length > 0 || detailPage) {
            // Only refresh if user is on the page and visible
            if (!document.hidden) {
                // In a real application, you would make an AJAX call here
                // to check for updated order statuses
                console.log('Checking for order status updates...');
            }
        }
    }

    // Set up periodic status refresh (every 30 seconds)
    setInterval(refreshOrderStatus, 30000);

    // Handle page visibility changes
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Page is visible again, refresh status
            refreshOrderStatus();
        }
    });

    // Form validation helpers
    function validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });

        return isValid;
    }

    // Add loading states to all form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton && !submitButton.disabled) {
                const originalText = submitButton.innerHTML || submitButton.value;
                
                if (submitButton.tagName === 'BUTTON') {
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                } else {
                    submitButton.value = 'Memproses...';
                }
                
                submitButton.disabled = true;

                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    if (submitButton.tagName === 'BUTTON') {
                        submitButton.innerHTML = originalText;
                    } else {
                        submitButton.value = originalText;
                    }
                    submitButton.disabled = false;
                }, 5000);
            }
        });
    });

    // Copy order ID functionality
    function copyOrderId(orderId) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(orderId).then(() => {
                showToast('ID Pesanan berhasil disalin!', 'success');
            }).catch(() => {
                fallbackCopyTextToClipboard(orderId);
            });
        } else {
            fallbackCopyTextToClipboard(orderId);
        }
    }

    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showToast('ID Pesanan berhasil disalin!', 'success');
        } catch (err) {
            showToast('Gagal menyalin ID Pesanan', 'error');
        }

        document.body.removeChild(textArea);
    }

    // Toast notification system
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;

        // Add toast styles if not already present
        if (!document.querySelector('#toast-styles')) {
            const toastStyles = document.createElement('style');
            toastStyles.id = 'toast-styles';
            toastStyles.textContent = `
                .toast {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 16px;
                    border-radius: 5px;
                    color: white;
                    font-weight: 600;
                    z-index: 1000;
                    opacity: 0;
                    transform: translateX(100%);
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    max-width: 300px;
                }
                .toast.show {
                    opacity: 1;
                    transform: translateX(0);
                }
                .toast-success { background-color: #28a745; }
                .toast-error { background-color: #dc3545; }
                .toast-info { background-color: #17a2b8; }
            `;
            document.head.appendChild(toastStyles);
        }

        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Hide and remove toast
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Add click handlers for order IDs (to copy them)
    const orderIds = document.querySelectorAll('.order-info h4, .detail-header h2');
    orderIds.forEach(element => {
        element.style.cursor = 'pointer';
        element.title = 'Klik untuk menyalin ID pesanan';
        
        element.addEventListener('click', function() {
            const text = this.textContent;
            const orderId = text.match(/#(\w+)/);
            if (orderId && orderId[1]) {
                copyOrderId(orderId[1]);
            }
        });
    });

    console.log('Order History page initialized successfully');
});