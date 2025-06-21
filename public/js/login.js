// Toggle password visibility
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = passwordInput.nextElementSibling;
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = 'Sembunyikan';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = 'Lihat';
    }
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.querySelector('.login-btn');

    // Pastikan semua element ada
    if (!loginForm || !emailInput || !passwordInput || !loginBtn) {
        console.error('Required form elements not found');
        return;
    }

    // Store original button text
    const originalButtonText = loginBtn.textContent;
    let isSubmitting = false;
    let loadingTimeout;

    // Function to reset loading state
    function resetLoadingState() {
        isSubmitting = false;
        loginBtn.disabled = false;
        loginBtn.textContent = originalButtonText;
        document.body.classList.remove('loading');
        
        if (loadingTimeout) {
            clearTimeout(loadingTimeout);
            loadingTimeout = null;
        }
    }

    // Function to set loading state
    function setLoadingState() {
        isSubmitting = true;
        loginBtn.disabled = true;
        loginBtn.textContent = 'Memproses...';
        document.body.classList.add('loading');
        
        // Safety timeout to reset loading state (10 seconds)
        loadingTimeout = setTimeout(function() {
            resetLoadingState();
            showAlert('Koneksi timeout. Silakan coba lagi.', 'error');
        }, 10000);
    }

    // Function to show alert
    function showAlert(message, type = 'error') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.innerHTML = `<p>${message}</p>`;
        
        const loginContainer = document.querySelector('.login-container');
        const form = document.getElementById('loginForm');
        loginContainer.insertBefore(alertDiv, form);
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            alertDiv.style.opacity = '0';
            alertDiv.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alertDiv.remove(), 500);
        }, 5000);
    }

    // Email validation
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Password validation
    function validatePassword(password) {
        return password.length >= 6;
    }

    // Show field error
    function showFieldError(field, message) {
        hideFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.color = '#dc3545';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '0.25rem';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
        field.style.borderColor = '#dc3545';
    }

    // Hide field error
    function hideFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.style.borderColor = '';
    }

    // Real-time validation
    emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email && !validateEmail(email)) {
            showFieldError(this, 'Format email tidak valid');
        } else {
            hideFieldError(this);
        }
    });

    passwordInput.addEventListener('blur', function() {
        const password = this.value;
        if (password && !validatePassword(password)) {
            showFieldError(this, 'Password minimal 6 karakter');
        } else {
            hideFieldError(this);
        }
    });

    // Clear errors on input
    emailInput.addEventListener('input', function() {
        hideFieldError(this);
    });

    passwordInput.addEventListener('input', function() {
        hideFieldError(this);
    });

    // Form submission handler
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default submission first
        
        // Prevent double submission
        if (isSubmitting) {
            return false;
        }

        const email = emailInput.value.trim();
        const password = passwordInput.value;

        // Clear previous field errors
        hideFieldError(emailInput);
        hideFieldError(passwordInput);

        let hasError = false;

        // Validate email
        if (!email) {
            showFieldError(emailInput, 'Email wajib diisi');
            hasError = true;
        } else if (!validateEmail(email)) {
            showFieldError(emailInput, 'Format email tidak valid');
            hasError = true;
        }

        // Validate password
        if (!password) {
            showFieldError(passwordInput, 'Password wajib diisi');
            hasError = true;
        } else if (!validatePassword(password)) {
            showFieldError(passwordInput, 'Password minimal 6 karakter');
            hasError = true;
        }

        // If there are errors, stop here
        if (hasError) {
            emailInput.focus();
            return false;
        }

        // Set loading state
        setLoadingState();

        // Create form data
        const formData = new FormData(loginForm);

        // Submit using fetch for better error handling
        fetch(loginForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.redirected) {
                // If redirected, go to the new location
                window.location.href = response.url;
                return;
            }
            return response.text();
        })
        .then(data => {
            if (typeof data === 'string' && data.includes('alert-error')) {
                // If response contains error, parse and show it
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
                const errorAlert = doc.querySelector('.alert-error');
                if (errorAlert) {
                    showAlert(errorAlert.textContent.trim(), 'error');
                }
                resetLoadingState();
            } else if (typeof data === 'string') {
                // Replace page content if it's HTML
                document.open();
                document.write(data);
                document.close();
            }
        })
        .catch(error => {
            console.error('Login error:', error);
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
            resetLoadingState();
        });
    });

    // Enter key handling
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !isSubmitting) {
            if (document.activeElement === emailInput) {
                passwordInput.focus();
            } else if (document.activeElement === passwordInput) {
                loginForm.dispatchEvent(new Event('submit'));
            }
        }
    });

    // Reset loading state when page loads (for back button scenarios)
    window.addEventListener('pageshow', function(event) {
        resetLoadingState();
    });

    // Reset loading state before page unloads
    window.addEventListener('beforeunload', function() {
        resetLoadingState();
    });

    // Auto-hide existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});

// Accessibility improvements
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.toggle-password');
    
    // Add ARIA labels
    if (emailInput) emailInput.setAttribute('aria-label', 'Masukkan alamat email');
    if (passwordInput) passwordInput.setAttribute('aria-label', 'Masukkan password');
    if (toggleButton) toggleButton.setAttribute('aria-label', 'Toggle password visibility');
    
    // Add focus indicators
    const focusableElements = document.querySelectorAll('input, button, a');
    focusableElements.forEach(function(element) {
        element.addEventListener('focus', function() {
            this.style.outline = '2px solid #FF5040';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
});