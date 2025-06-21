<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $menu->menu_name }} - InkluSwift</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/description.css') }}">
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

    @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button class="alert-close" onclick="closeAlert('successAlert')">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" id="errorAlert">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button class="alert-close" onclick="closeAlert('errorAlert')">&times;</button>
        </div>
    @endif

    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <li><a href="{{ route('description') }}" class="active">Deskripsi</a></li>
        </ul>
    </nav>

    <main class="product-container">
        <div class="product-image">
            <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('Assets/default-food.png') }}" 
                 alt="{{ $menu->menu_name }}">
        </div>

        <!-- BAGIAN YANG TELAH DIPERBAIKI -->
        <div class="product-details">
            <h1 class="product-title">{{ $menu->menu_name }}</h1>

            <div class="product-price">
                <span class="current-price">Rp {{ number_format($discountedPrice, 0, ',', '.') }}</span>
                @if($menu->discount > 0)
                    <span class="original-price">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                    <span class="discount-badge">{{ $menu->discount }}% OFF</span>
                @endif
            </div>

            <!-- Updated Form Section -->
            <div class="add-to-cart-section">
                <div class="quantity-selector">
                    <label for="quantity">Jumlah:</label>
                    <div class="quantity-controls">
                        <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="99">
                        <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <button type="button" class="add-to-cart" onclick="addToCart('{{ $menu->menu_id }}')">
                    <i class="fas fa-shopping-cart"></i>
                    Tambahkan ke keranjang
                </button>
            </div>

            <div class="info-section">
                <div class="info-header" onclick="toggleSection('deskripsi')">
                    <h3>Deskripsi</h3>
                    <img src="{{ asset('Assets/chevron-up.svg') }}" alt="Toggle" class="chevron up" id="deskripsi-chevron">
                </div>
                <div class="info-content" id="deskripsi-content">
                    <p>{{ $menu->description ?? 'Deskripsi tidak tersedia' }}</p>
                </div>
            </div>

            <div class="info-section">
                <div class="info-header" onclick="toggleSection('bahan')">
                    <h3>Bahan</h3>
                    <img src="{{ asset('Assets/chevron-up.svg') }}" alt="Toggle" class="chevron up" id="bahan-chevron">
                </div>
                <div class="info-content" id="bahan-content">
                    <p>{{ $menu->ingredients ?? 'Informasi bahan tidak tersedia' }}</p>
                </div>
            </div>

            <div class="info-section">
                <div class="info-header" onclick="toggleSection('penyimpanan')">
                    <h3>Penyimpanan</h3>
                    <img src="{{ asset('Assets/chevron-up.svg') }}" alt="Toggle" class="chevron up" id="penyimpanan-chevron">
                </div>
                <div class="info-content" id="penyimpanan-content">
                    <p>{{ $menu->storage ?? 'Informasi penyimpanan tidak tersedia' }}</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Menambahkan ke keranjang...</p>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alert-container"></div>

    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <script>
        const menuData = {
            id: '{{ $menu->menu_id }}',
            name: '{{ $menu->menu_name }}',
            price: {{ $discountedPrice }},
            originalPrice: {{ $originalPrice }},
            discount: {{ $menu->discount ?? 0 }}
        };
    </script>
    <script src="{{ asset('js/description.js') }}"></script>
</body>
</html>
