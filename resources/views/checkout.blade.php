<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - InkluSwift</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman -->
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <!-- Script aksesibilitas -->
    <script src="{{ asset('js/accessibility.js') }}"></script>
</head>
<body>
    
    <header>
        <div class="logo">
            <img src="{{ asset('Assets/logo hd.png') }}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>
        <div class="auth-buttons">
            <a href="{{ route('cart') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/cart.png') }}" alt="Cart">
                Keranjang (<span id="cart-count">{{ $cartItems->sum('quantity') }}</span>)
            </a>
            <a href="#" class="btn btn-primary">
                <img src="{{ asset('Assets/profile.png') }}" alt="Profile">
                Profil
            </a>
        </div>
    </header>
    
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <!-- <li><a href="{{ route('description') }}">Deskripsi</a></li> -->
            <li><a href="{{ route('cart') }}">Keranjang</a></li>
            <li><a href="#" class="active">Checkout</a></li>
        </ul>
    </nav>
    
    <main class="checkout-container">
        <div class="checkout-header">
            <h1>Checkout Pesanan</h1>
            <div class="breadcrumb">
                <a href="{{ route('cart') }}">Keranjang</a>
                <i class="fas fa-chevron-right"></i>
                <span>Checkout</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="checkout-content">
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-card">
                    <h3><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>
                    
                    <div class="order-items">
                        @foreach($cartItems as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ $item->menu->image ? asset('storage/' . $item->menu->image) : asset('Assets/default-food.png') }}" 
                                         alt="{{ $item->menu->menu_name }}">
                                </div>
                                <div class="item-details">
                                    <h4>{{ $item->menu->menu_name }}</h4>
                                    <p class="item-quantity">{{ $item->quantity }}x</p>
                                    <p class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    @if($item->menu->discount > 0)
                                        <p class="item-discount">Diskon: {{ $item->menu->discount }}%</p>
                                    @endif
                                </div>
                                <div class="item-total">
                                    Rp {{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="summary-calculations">
                        <div class="calc-row">
                            <span>Subtotal:</span>
                            <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        @if($totalDiscount > 0)
                            <div class="calc-row discount">
                                <span>Diskon:</span>
                                <span>- Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="calc-row">
                            <span>Setelah Diskon:</span>
                            <span>Rp {{ number_format($subtotalAfterDiscount, 0, ',', '.') }}</span>
                        </div>
                        <div class="calc-row">
                            <span>Biaya Layanan:</span>
                            <span>Rp {{ number_format($serviceCharge, 0, ',', '.') }}</span>
                        </div>
                        <div class="calc-row total">
                            <span>Total Pembayaran:</span>
                            <span>Rp {{ number_format($finalAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="checkout-form">
                <form id="checkout-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Customer Information -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Informasi Pelanggan</h3>
                        
                        <div class="form-group">
                            <label for="customer_name">Nama Lengkap *</label>
                            <input type="text" id="customer_name" name="customer_name" required maxlength="100">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Nomor Telepon *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" required maxlength="20" placeholder="08xxxxxxxxxx">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_address">Alamat Lengkap *</label>
                            <textarea id="customer_address" name="customer_address" required maxlength="500" rows="3" placeholder="Masukkan alamat lengkap untuk pengiriman"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Catatan Tambahan</label>
                            <textarea id="notes" name="notes" maxlength="500" rows="2" placeholder="Catatan khusus untuk pesanan (opsional)"></textarea>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3><i class="fas fa-credit-card"></i> Metode Pembayaran</h3>
                        
                        <div class="payment-methods">
                            @foreach($paymentMethods as $method)
                                <div class="payment-method" data-method-id="{{ $method->method_id }}">
                                    <input type="radio" 
                                           id="method_{{ $method->method_id }}" 
                                           name="method_id" 
                                           value="{{ $method->method_id }}"
                                           onchange="selectPaymentMethod('{{ $method->method_id }}')">
                                    <label for="method_{{ $method->method_id }}">
                                        <div class="method-info">
                                            <h4>{{ $method->method_name }}</h4>
                                            <p>{{ $method->description }}</p>
                                            @if($method->is_cod)
                                                <span class="method-badge cod">COD</span>
                                            @endif
                                            @if($method->need_proof)
                                                <span class="method-badge proof">Perlu Bukti</span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Payment Details -->
                        <div id="payment-details" style="display: none;">
                            <div class="payment-info">
                                <div id="payment-instructions"></div>
                                
                                <!-- Account Information -->
                                <div id="payment-account" style="display: none;">
                                    <h4>Informasi Rekening:</h4>
                                    <div class="account-info">
                                        <span id="account-number"></span>
                                        <button type="button" class="copy-btn" onclick="copyAccountNumber()">
                                            <i class="fas fa-copy"></i> Salin
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Proof Upload -->
                        <div id="proof-upload" style="display: none;">
                            <div class="form-group">
                                <label for="proof_image">Upload Bukti Pembayaran</label>
                                <div class="file-upload">
                                    <input type="file" id="proof_image" name="proof_image" accept="image/jpeg,image/png,image/jpg">
                                    <div class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Pilih file atau drag & drop</span>
                                        <small>Format: JPG, PNG. Maksimal 2MB</small>
                                    </div>
                                </div>
                                <div id="file-preview" style="display: none;">
                                    <img id="preview-image" src="" alt="Preview">
                                    <button type="button" onclick="removeFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-check"></i> Buat Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memproses pesanan...</p>
        </div>
    </div>
    
    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <script src="{{ asset('js/checkout.js') }}"></script>
</body>
</html>