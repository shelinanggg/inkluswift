<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - InkluSwift</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">  
    <!-- CSS halaman -->
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Script aksesibilitas -->
    <script src="{{ asset('js/accessibility.js') }}"></script>
</head>
<body>
    
    <header>
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
        </div>
        <div class="auth-buttons">
            <a href="{{ route('cart') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/cart.png') }}" alt="Cart">
                Keranjang (<span id="cart-count">{{ $cartItems->sum('quantity') }}</span>)
            </a>
            <a href="{{ route('edit-profile') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/profile.png') }}" alt="Profile">
                Profil
            </a>
        </div>
    </header>
    
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <!-- <li><a href="{{ route('description') }}">Deskripsi</a></li> -->
            <li><a href="{{ route('cart') }}" class="active">Keranjang</a></li>
        </ul>
    </nav>
    
    <main class="cart-container">
        <div class="cart-header">
            <h1>Keranjang Belanja</h1>
            @if($cartItems->count() > 0)
                <button class="clear-cart-btn" onclick="clearAllCart()">
                    <i class="fas fa-trash"></i> Kosongkan Keranjang
                </button>
            @endif
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

        @if($cartItems->count() > 0)
            <div class="cart-content">
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item" data-cart-id="{{ $item->id }}">
                            <div class="item-image">
                                <img src="{{ $item->menu->image ? asset('storage/' . $item->menu->image) : asset('Assets/default-food.png') }}" 
                                     alt="{{ $item->menu->menu_name }}">
                            </div>
                            
                            <div class="item-details">
                                <h3 class="item-name">{{ $item->menu->menu_name }}</h3>
                                <p class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                @if($item->menu->discount > 0)
                                    <p class="item-discount">Diskon: {{ $item->menu->discount }}%</p>
                                @endif
                            </div>
                            
                            <div class="item-quantity">
                                <button class="quantity-btn minus" onclick="updateQuantity({{ $item->id }}, 'minus')">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" 
                                       class="quantity-input" 
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       id="quantity-{{ $item->id }}"
                                       onchange="updateQuantityInput({{ $item->id }})">
                                <button class="quantity-btn plus" onclick="updateQuantity({{ $item->id }}, 'plus')">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <div class="item-subtotal">
                                <p class="subtotal-amount" id="subtotal-{{ $item->id }}">
                                    Rp {{ number_format($item->subtotal_after_discount, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="item-actions">
                                <button class="remove-btn" onclick="removeItem({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Ringkasan Pesanan</h3>
                        <div class="summary-row">
                            <span>Total Item:</span>
                            <span id="total-items">{{ $cartItems->sum('quantity') }} item</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total Pembayaran:</span>
                            <span id="total-amount">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        <button class="checkout-btn" onclick="proceedToCheckout()">
                            <i class="fas fa-credit-card"></i>
                            Lanjut ke Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2>Keranjang Belanja Kosong</h2>
                <p>Belum ada item yang ditambahkan ke keranjang belanja Anda.</p>
                <a href="{{ route('home') }}" class="continue-shopping-btn">
                    <i class="fas fa-arrow-left"></i>
                    Lanjut Berbelanja
                </a>
            </div>
        @endif
    </main>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Memproses...</p>
        </div>
    </div>
    
    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>