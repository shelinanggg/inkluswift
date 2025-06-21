// Profile editing functionality
let editingFields = new Set();
let originalValues = {};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeProfilePicturePreview();
    initializeFormValues();
    hideAlerts();
});

// Initialize profile picture preview functionality
function initializeProfilePicturePreview() {
    const profilePictureInput = document.getElementById('profilePictureInput');
    
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                previewProfilePicture(file);
                showSaveButton();
            }
        });
    }
}

// Initialize original form values for cancel functionality
function initializeFormValues() {
    const fields = ['name', 'email', 'phone', 'address'];
    fields.forEach(field => {
        const displayElement = document.getElementById(`${field}-display`);
        if (displayElement) {
            originalValues[field] = displayElement.textContent;
        }
    });
}

// Toggle edit mode for a specific field
function toggleEdit(field) {
    const displayElement = document.getElementById(`${field}-display`);
    const inputElement = document.getElementById(`${field}-input`);
    const editBtn = displayElement.parentNode.querySelector('.edit-btn');
    
    if (!displayElement || !inputElement) return;
    
    // If field is already being edited, save it
    if (editingFields.has(field)) {
        saveField(field);
        return;
    }
    
    // Store original value
    originalValues[field] = displayElement.textContent;
    
    // Switch to edit mode
    displayElement.classList.add('hidden');
    inputElement.classList.remove('hidden');
    inputElement.focus();
    
    // Update button text
    editBtn.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Simpan
    `;
    
    editingFields.add(field);
    showSaveButton();
    
    // Handle Enter key to save
    inputElement.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveField(field);
        }
    });
    
    // Handle Escape key to cancel
    inputElement.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            e.preventDefault();
            cancelFieldEdit(field);
        }
    });
}

// Save individual field
function saveField(field) {
    const displayElement = document.getElementById(`${field}-display`);
    const inputElement = document.getElementById(`${field}-input`);
    const editBtn = displayElement.parentNode.querySelector('.edit-btn');
    
    if (!displayElement || !inputElement) return;
    
    // Update display with new value
    const newValue = inputElement.value.trim();
    displayElement.textContent = newValue || 'Belum diisi';
    
    // Switch back to display mode
    inputElement.classList.add('hidden');
    displayElement.classList.remove('hidden');
    
    // Update button text back to edit
    editBtn.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Edit
    `;
    
    editingFields.delete(field);
    
    // Hide save button if no fields are being edited
    if (editingFields.size === 0 && !hasProfilePictureChanged()) {
        hideSaveButton();
    }
}

// Cancel edit for individual field
function cancelFieldEdit(field) {
    const displayElement = document.getElementById(`${field}-display`);
    const inputElement = document.getElementById(`${field}-input`);
    const editBtn = displayElement.parentNode.querySelector('.edit-btn');
    
    if (!displayElement || !inputElement) return;
    
    // Restore original value
    inputElement.value = originalValues[field] === 'Belum diisi' ? '' : originalValues[field];
    
    // Switch back to display mode
    inputElement.classList.add('hidden');
    displayElement.classList.remove('hidden');
    
    // Update button text back to edit
    editBtn.innerHTML = `
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Edit
    `;
    
    editingFields.delete(field);
    
    // Hide save button if no fields are being edited
    if (editingFields.size === 0 && !hasProfilePictureChanged()) {
        hideSaveButton();
    }
}

// Cancel all edits
function cancelEdit() {
    // Cancel all field edits
    editingFields.forEach(field => {
        cancelFieldEdit(field);
    });
    
    // Reset profile picture
    const profilePictureInput = document.getElementById('profilePictureInput');
    if (profilePictureInput) {
        profilePictureInput.value = '';
        resetProfilePicturePreview();
    }
    
    editingFields.clear();
    hideSaveButton();
}

// Preview profile picture
function previewProfilePicture(file) {
    const currentPic = document.getElementById('currentProfilePic');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create or update image element
            if (currentPic.tagName === 'IMG') {
                currentPic.src = e.target.result;
            } else {
                // Replace default avatar with image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Profile Picture';
                img.id = 'currentProfilePic';
                currentPic.parentNode.replaceChild(img, currentPic);
            }
        };
        reader.readAsDataURL(file);
    }
}

// Reset profile picture preview
function resetProfilePicturePreview() {
    const currentPic = document.getElementById('currentProfilePic');
    const userName = document.querySelector('.profile-header span').textContent;
    
    // Check if there's an original profile picture
    const originalSrc = currentPic.getAttribute('data-original-src');
    
    if (originalSrc) {
        // Restore original image
        if (currentPic.tagName !== 'IMG') {
            const img = document.createElement('img');
            img.src = originalSrc;
            img.alt = 'Profile Picture';
            img.id = 'currentProfilePic';
            img.setAttribute('data-original-src', originalSrc);
            currentPic.parentNode.replaceChild(img, currentPic);
        } else {
            currentPic.src = originalSrc;
        }
    } else {
        // Restore default avatar
        if (currentPic.tagName === 'IMG') {
            const div = document.createElement('div');
            div.className = 'default-avatar';
            div.id = 'currentProfilePic';
            div.textContent = userName.charAt(0).toUpperCase();
            currentPic.parentNode.replaceChild(div, currentPic);
        }
    }
}

// Check if profile picture has changed
function hasProfilePictureChanged() {
    const profilePictureInput = document.getElementById('profilePictureInput');
    return profilePictureInput && profilePictureInput.files.length > 0;
}

// Remove profile picture
function removeProfilePicture() {
    if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
        const form = document.getElementById('removeProfilePictureForm');
        if (form) {
            form.submit();
        }
    }
}

// Show save button
function showSaveButton() {
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    
    if (saveButton) saveButton.style.display = 'inline-block';
    if (cancelButton) cancelButton.style.display = 'inline-block';
}

// Hide save button
function hideSaveButton() {
    const saveButton = document.getElementById('saveButton');
    const cancelButton = document.getElementById('cancelButton');
    
    if (saveButton) saveButton.style.display = 'none';
    if (cancelButton) cancelButton.style.display = 'none';
}

// Hide alerts after 5 seconds
function hideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
}

// Form validation before submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.profile-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Basic validation
            const nameInput = document.getElementById('name-input');
            const emailInput = document.getElementById('email-input');
            
            if (nameInput && !nameInput.classList.contains('hidden')) {
                if (!nameInput.value.trim()) {
                    e.preventDefault();
                    alert('Nama tidak boleh kosong');
                    nameInput.focus();
                    return;
                }
            }
            
            if (emailInput && !emailInput.classList.contains('hidden')) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailInput.value)) {
                    e.preventDefault();
                    alert('Format email tidak valid');
                    emailInput.focus();
                    return;
                }
            }
            
            // Show loading state
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                submitButton.disabled = true;
            }
        });
    }
});

// Store original profile picture source for reset functionality
document.addEventListener('DOMContentLoaded', function() {
    const currentPic = document.getElementById('currentProfilePic');
    if (currentPic && currentPic.tagName === 'IMG') {
        currentPic.setAttribute('data-original-src', currentPic.src);
    }
});

// Handle file input change for profile picture
document.addEventListener('DOMContentLoaded', function() {
    const profilePictureInput = document.getElementById('profilePictureInput');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    e.target.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus jpeg, png, jpg, atau gif');
                    e.target.value = '';
                    return;
                }
                
                previewProfilePicture(file);
                showSaveButton();
            }
        });
    }
});

// Password Change Functionality
function togglePasswordVisibility(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = passwordField.parentNode.querySelector('.password-toggle-icon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
        passwordField.type = 'password';
        toggleIcon.innerHTML = '<i class="fas fa-eye"></i>';
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 6) strength++;
    else feedback.push('Minimal 6 karakter');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength++;
    else feedback.push('Minimal 1 huruf besar');
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength++;
    else feedback.push('Minimal 1 huruf kecil');
    
    // Number check
    if (/\d/.test(password)) strength++;
    else feedback.push('Minimal 1 angka');
    
    // Special character check
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
    else feedback.push('Minimal 1 karakter khusus');
    
    return { strength, feedback };
}

// Update password strength indicator
function updatePasswordStrength(password) {
    const strengthIndicator = document.querySelector('.password-strength');
    const strengthText = document.querySelector('.password-strength-text');
    const strengthBar = document.querySelector('.strength-bar');
    
    if (!strengthIndicator) return;
    
    const { strength, feedback } = checkPasswordStrength(password);
    
    // Update strength bar
    if (strengthBar) {
        strengthBar.style.width = (strength / 5) * 100 + '%';
        
        // Color based on strength
        if (strength <= 2) {
            strengthBar.style.backgroundColor = '#ff4757';
        } else if (strength <= 3) {
            strengthBar.style.backgroundColor = '#ffa502';
        } else if (strength <= 4) {
            strengthBar.style.backgroundColor = '#3742fa';
        } else {
            strengthBar.style.backgroundColor = '#2ed573';
        }
    }
    
    // Update strength text
    if (strengthText) {
        if (password.length === 0) {
            strengthText.textContent = '';
        } else if (strength <= 2) {
            strengthText.textContent = 'Lemah';
            strengthText.className = 'password-strength-text weak';
        } else if (strength <= 3) {
            strengthText.textContent = 'Sedang';
            strengthText.className = 'password-strength-text medium';
        } else if (strength <= 4) {
            strengthText.textContent = 'Kuat';
            strengthText.className = 'password-strength-text strong';
        } else {
            strengthText.textContent = 'Sangat Kuat';
            strengthText.className = 'password-strength-text very-strong';
        }
    }
}

// Password confirmation checker
function checkPasswordConfirmation() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const confirmFeedback = document.querySelector('.password-confirm-feedback');
    
    if (!newPassword || !confirmPassword || !confirmFeedback) return;
    
    if (confirmPassword.value.length === 0) {
        confirmFeedback.textContent = '';
        confirmFeedback.className = 'password-confirm-feedback';
        return;
    }
    
    if (newPassword.value === confirmPassword.value) {
        confirmFeedback.textContent = 'Password cocok';
        confirmFeedback.className = 'password-confirm-feedback match';
    } else {
        confirmFeedback.textContent = 'Password tidak cocok';
        confirmFeedback.className = 'password-confirm-feedback no-match';
    }
}

// Initialize password functionality
document.addEventListener('DOMContentLoaded', function() {
    // Password strength checker
    const newPasswordField = document.getElementById('new_password');
    if (newPasswordField) {
        newPasswordField.addEventListener('input', function() {
            updatePasswordStrength(this.value);
        });
    }
    
    // Password confirmation checker
    const confirmPasswordField = document.getElementById('new_password_confirmation');
    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('input', checkPasswordConfirmation);
    }
    
    // Also check when new password changes
    if (newPasswordField) {
        newPasswordField.addEventListener('input', checkPasswordConfirmation);
    }
    
    // Password form validation
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password');
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('new_password_confirmation');
            
            // Check if all fields are filled
            if (!currentPassword.value.trim()) {
                e.preventDefault();
                alert('Password lama wajib diisi');
                currentPassword.focus();
                return;
            }
            
            if (!newPassword.value.trim()) {
                e.preventDefault();
                alert('Password baru wajib diisi');
                newPassword.focus();
                return;
            }
            
            if (!confirmPassword.value.trim()) {
                e.preventDefault();
                alert('Konfirmasi password wajib diisi');
                confirmPassword.focus();
                return;
            }
            
            // Check password strength
            const { strength } = checkPasswordStrength(newPassword.value);
            if (strength < 2) {
                e.preventDefault();
                alert('Password terlalu lemah. Gunakan kombinasi huruf besar, huruf kecil, angka, dan karakter khusus.');
                newPassword.focus();
                return;
            }
            
            // Check password confirmation
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok');
                confirmPassword.focus();
                return;
            }
            
            // Check if new password is different from current
            if (currentPassword.value === newPassword.value) {
                e.preventDefault();
                alert('Password baru harus berbeda dari password lama');
                newPassword.focus();
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengubah Password...';
                submitButton.disabled = true;
            }
        });
    }
});

// Clear password form
function clearPasswordForm() {
    const passwordForm = document.getElementById('password-form');
    if (passwordForm) {
        passwordForm.reset();
        
        // Clear strength indicator
        const strengthBar = document.querySelector('.strength-bar');
        const strengthText = document.querySelector('.password-strength-text');
        const confirmFeedback = document.querySelector('.password-confirm-feedback');
        
        if (strengthBar) {
            strengthBar.style.width = '0%';
        }
        
        if (strengthText) {
            strengthText.textContent = '';
            strengthText.className = 'password-strength-text';
        }
        
        if (confirmFeedback) {
            confirmFeedback.textContent = '';
            confirmFeedback.className = 'password-confirm-feedback';
        }
    }
}

// Auto-clear password form after successful change
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message for password change
    const alerts = document.querySelectorAll('.alert-success');
    alerts.forEach(alert => {
        if (alert.textContent.includes('Password berhasil diubah')) {
            setTimeout(() => {
                clearPasswordForm();
            }, 2000);
        }
    });
});