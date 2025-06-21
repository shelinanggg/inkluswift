// Food data management
function setFoodData(id, title, price, originalPrice, description, ingredients, storage, image) {
    const foodData = {
        id: id,
        title: title,
        price: price,
        originalPrice: originalPrice,
        description: description,
        ingredients: ingredients,
        storage: storage,
        image: image
    };
    
    // Store in sessionStorage instead of localStorage
    sessionStorage.setItem('foodData', JSON.stringify(foodData));
    
    // Navigate to description page
    window.location.href = '/description';
}

// Search functionality enhancement
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-bar');
    
    if (searchForm) {
        const searchInput = searchForm.querySelector('input[name="q"]');
        
        if (searchInput) {
            // Add search suggestions
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                if (query.length > 2) {
                    let searchHistory = JSON.parse(localStorage.getItem('searchHistory') || '[]');
                    
                    if (!searchHistory.includes(query)) {
                        searchHistory.unshift(query);
                        searchHistory = searchHistory.slice(0, 5);
                        localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
                    }
                }
            });
        }
        
        // Handle form submission
        searchForm.addEventListener('submit', function(e) {
            const query = searchInput ? searchInput.value.trim() : '';
            
            if (!query) {
                e.preventDefault();
                alert('Silakan masukkan kata kunci pencarian');
                return;
            }
            
            sessionStorage.setItem('lastSearchQuery', query);
        });
    }
});

// Smooth scrolling for category links
document.addEventListener('DOMContentLoaded', function() {
    const categoryLinks = document.querySelectorAll('.category');
    
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            this.style.opacity = '0.7';
            
            setTimeout(() => {
                this.style.opacity = '1';
            }, 300);
        });
    });
});

// FIXED: Improved image loading without flickering
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img');
    
    // Handle image loading properly
    images.forEach(img => {
        // Set initial state - don't hide images by default
        img.style.transition = 'opacity 0.3s ease';
        
        // If image is already loaded (cached), don't animate
        if (img.complete && img.naturalHeight !== 0) {
            img.style.opacity = '1';
            return;
        }
        
        // Only apply loading animation to images that aren't loaded yet
        img.style.opacity = '0.3';
        
        // Handle successful load
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        // Handle error loading
        img.addEventListener('error', function() {
            this.style.opacity = '1'; // Still show the fallback image
        });
    });
    
    // Optional: Use Intersection Observer for images below the fold only
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    
    if (lazyImages.length > 0 && 'IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    // Remove loading attribute to trigger loading
                    img.removeAttribute('loading');
                    
                    // Stop observing this image
                    observer.unobserve(img);
                }
            });
        }, {
            // Start loading when image is 100px away from viewport
            rootMargin: '100px'
        });
        
        lazyImages.forEach(img => {
            imageObserver.observe(img);
        });
    }
});

// Add to cart functionality
function addToCart(menuId, quantity = 1) {
    let cart = JSON.parse(sessionStorage.getItem('cart') || '[]');
    
    const existingItem = cart.find(item => item.id === menuId);
    
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({
            id: menuId,
            quantity: quantity,
            addedAt: new Date().toISOString()
        });
    }
    
    sessionStorage.setItem('cart', JSON.stringify(cart));
    showNotification('Item berhasil ditambahkan ke keranjang!');
}

// Improved notification system
function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 12px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
        font-family: Arial, sans-serif;
        font-size: 14px;
        max-width: 300px;
        word-wrap: break-word;
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    requestAnimationFrame(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    });
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}