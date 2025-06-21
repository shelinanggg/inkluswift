// Global variables
let selectedPaymentMethod = null;
let currentAccountNumber = '';

// DOM elements
const checkoutForm = document.getElementById('checkout-form');
const submitBtn = document.getElementById('submit-btn');
const loadingOverlay = document.getElementById('loading-overlay');
const proofUpload = document.getElementById('proof-upload');
const paymentDetails = document.getElementById('payment-details');
const fileInput = document.getElementById('proof_image');
const filePreview = document.getElementById('file-preview');
const previewImage = document.getElementById('preview-image');

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    setupCSRFToken();
});

// Setup CSRF token for all AJAX requests
function setupCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Set default headers for fetch requests
    window.csrfToken = token;
}

// Initialize all event listeners
function initializeEventListeners() {
    // Form submission
    checkoutForm.addEventListener('submit', handleCheckoutSubmit);
    
    // File upload handling
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
    
    // Drag and drop for file upload
    const fileUpload = document.querySelector('.file-upload');
    if (fileUpload) {
        fileUpload.addEventListener('dragover', handleDragOver);
        fileUpload.addEventListener('dragleave', handleDragLeave);
        fileUpload.addEventListener('drop', handleFileDrop);
        fileUpload.addEventListener('click', () => fileInput.click());
    }
}

// Handle payment method selection
function selectPaymentMethod(methodId) {
    selectedPaymentMethod = methodId;
    
    // Show loading
    showLoading();
    
    // Fetch payment method details
    fetch(`/checkout/payment-method/${methodId}`)
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                displayPaymentDetails(data.data);
            } else {
                showAlert('error', data.message || 'Gagal memuat detail pembayaran');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('error', 'Terjadi kesalahan saat memuat detail pembayaran');
        });
}

// Display payment method details
function displayPaymentDetails(paymentData) {
    const paymentDetailsDiv = document.getElementById('payment-details');
    const paymentInstructions = document.getElementById('payment-instructions');
    const paymentAccount = document.getElementById('payment-account');
    const accountNumber = document.getElementById('account-number');
    const proofUploadDiv = document.getElementById('proof-upload');
    
    // QR Code elements - Fixed: Get elements properly
    const staticProof = document.getElementById('static-proof');
    const staticProofImage = document.getElementById('static-proof-image');
    
    // Debug: Log the elements and data
    console.log('Payment Data:', paymentData);
    console.log('Static Proof Element:', staticProof);
    console.log('Static Proof Image Element:', staticProofImage);
    console.log('Static Proof URL:', paymentData.static_proof);
    
    // Show payment details section
    paymentDetailsDiv.style.display = 'block';
    
    // Set instructions
    if (paymentData.instructions) {
        paymentInstructions.innerHTML = `<h4>Instruksi Pembayaran:</h4><p>Transferkan ke nomor tujuan yang tertera di bawah ini</p>`;
    }
    
    // Show account info if available
    if (paymentData.destination_account) {
        currentAccountNumber = paymentData.destination_account;
        accountNumber.textContent = paymentData.destination_account;
        paymentAccount.style.display = 'block';
    } else {
        paymentAccount.style.display = 'none';
    }
    
    // Fixed: Handle QR code display properly
    if (paymentData.static_proof && staticProof && staticProofImage) {
        // Build proper image URL - handle both relative and absolute paths
        let imageUrl = paymentData.static_proof;
        
        // If it's a relative path, prepend with storage URL
        if (!imageUrl.startsWith('http') && !imageUrl.startsWith('/storage/')) {
            // Assuming Laravel storage structure
            imageUrl = '/storage/' + imageUrl;
        }
        
        console.log('Original static_proof:', paymentData.static_proof);
        console.log('Final image URL:', imageUrl);
        
        // Set image source
        staticProofImage.src = imageUrl;
        staticProofImage.alt = `QR Code ${paymentData.name || paymentData.method_name || 'Pembayaran'}`;
        
        // Show the QR code container
        staticProof.style.display = 'block';
        staticProofImage.style.display = 'block';
        
        // Add error handling for image loading
        staticProofImage.onerror = function() {
            console.error('Failed to load QR code image:', imageUrl);
            console.error('Original path:', paymentData.static_proof);
            staticProof.style.display = 'none';
            showAlert('warning', 'QR Code tidak dapat dimuat. Silakan gunakan nomor rekening atau hubungi customer service.');
        };
        
        // Add success handler
        staticProofImage.onload = function() {
            console.log('QR Code loaded successfully from:', imageUrl);
        };
        
        console.log('QR Code should now be visible');
    } else {
        // Hide QR code if not available
        if (staticProof) {
            staticProof.style.display = 'none';
        }
        
        // Debug: Log why QR code is not showing
        if (!paymentData.static_proof) {
            console.log('No static_proof URL provided');
        }
        if (!staticProof) {
            console.log('static-proof element not found in DOM');
        }
        if (!staticProofImage) {
            console.log('static-proof-image element not found in DOM');
        }
    }

    // Show/hide proof upload based on payment method
    if (paymentData.need_proof && !paymentData.is_cod) {
        proofUploadDiv.style.display = 'block';
        // Make proof upload required for non-COD methods that need proof
        fileInput.setAttribute('required', 'required');
    } else {
        proofUploadDiv.style.display = 'none';
        fileInput.removeAttribute('required');
    }

    // Update submit button text
    if (paymentData.is_cod) {
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Buat Pesanan (COD)';
    } else if (paymentData.need_proof) {
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Buat Pesanan & Upload Bukti';
    } else {
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Buat Pesanan';
    }
}

// Handle form submission
function handleCheckoutSubmit(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Show loading
    showLoading();
    submitBtn.disabled = true;
    
    // Prepare form data
    const formData = new FormData(checkoutForm);
    
    // Submit form
    fetch('/checkout/process', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        submitBtn.disabled = false;
        
        if (data.success) {
            showAlert('success', data.message);
            
            // Redirect to success page
            setTimeout(() => {
                window.location.href = data.redirect_url || '/checkout/success';
            }, 1500);
        } else {
            showAlert('error', data.message || 'Gagal memproses pesanan');
        }
    })
    .catch(error => {
        hideLoading();
        submitBtn.disabled = false;
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memproses pesanan');
    });
}

// Validate form before submission
function validateForm() {
    // Check if payment method is selected
    if (!selectedPaymentMethod) {
        showAlert('error', 'Silakan pilih metode pembayaran');
        return false;
    }
    
    // Check required fields
    const requiredFields = ['customer_name', 'customer_phone', 'customer_address'];
    for (let field of requiredFields) {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            showAlert('error', `${input.previousElementSibling.textContent} harus diisi`);
            input.focus();
            return false;
        }
    }
    
    // Validate phone number format
    const phone = document.getElementById('customer_phone').value;
    if (!/^[0-9+\-\s()]+$/.test(phone)) {
        showAlert('error', 'Format nomor telepon tidak valid');
        return false;
    }
    
    // Check if proof is required but not uploaded
    const proofRequired = fileInput.hasAttribute('required');
    if (proofRequired && !fileInput.files.length) {
        showAlert('error', 'Bukti pembayaran harus diupload untuk metode pembayaran ini');
        return false;
    }
    
    return true;
}

// Handle file selection
function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        validateAndPreviewFile(file);
    }
}

// Handle drag and drop
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('drag-over');
}

function handleDragLeave(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
}

function handleFileDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('drag-over');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        fileInput.files = files;
        validateAndPreviewFile(file);
    }
}

// Validate and preview uploaded file
function validateAndPreviewFile(file) {
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!allowedTypes.includes(file.type)) {
        showAlert('error', 'Format file harus JPG atau PNG');
        removeFile();
        return;
    }
    
    // Check file size (2MB = 2 * 1024 * 1024 bytes)
    const maxSize = 2 * 1024 * 1024;
    if (file.size > maxSize) {
        showAlert('error', 'Ukuran file maksimal 2MB');
        removeFile();
        return;
    }
    
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        filePreview.style.display = 'block';
        document.querySelector('.file-upload-label').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

// Remove selected file
function removeFile() {
    fileInput.value = '';
    filePreview.style.display = 'none';
    document.querySelector('.file-upload-label').style.display = 'block';
}

// Copy account number to clipboard
function copyAccountNumber() {
    if (currentAccountNumber) {
        navigator.clipboard.writeText(currentAccountNumber).then(() => {
            showAlert('success', 'Nomor rekening berhasil disalin');
        }).catch(() => {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = currentAccountNumber;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showAlert('success', 'Nomor rekening berhasil disalin');
        });
    }
}

// Go back to cart
function goBack() {
    window.location.href = '/cart';
}

// Show loading overlay
function showLoading() {
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';
    }
}

// Hide loading overlay
function hideLoading() {
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
}

// Show alert message
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <span>${message}</span>
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // Insert alert at the top of main content
    const checkoutHeader = document.querySelector('.checkout-header');
    checkoutHeader.insertAdjacentElement('afterend', alert);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
    
    // Scroll to alert
    alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
}