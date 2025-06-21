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

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    form.addEventListener('submit', function(e) {
        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }

        // Check password length
        if (password.value.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }

        // Check password max length (sesuai database constraint)
        if (password.value.length > 20) {
            e.preventDefault();
            alert('Password maksimal 20 karakter!');
            return false;
        }
    });

    // Real-time password confirmation check
    confirmPassword.addEventListener('input', function() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Password tidak cocok');
        } else {
            confirmPassword.setCustomValidity('');
        }
    });

    // File upload validation
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB!');
                    e.target.value = '';
                    return false;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file harus JPEG, PNG, JPG, atau GIF!');
                    e.target.value = '';
                    return false;
                }
            }
        });
    }

    // Phone number validation
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Allow only numbers, +, -, space, and parentheses
            const value = e.target.value;
            const regex = /^[0-9+\-\s()]*$/;
            if (!regex.test(value)) {
                e.target.value = value.slice(0, -1);
            }
        });
    }
});