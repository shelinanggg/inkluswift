<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift User Dashboard</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Script aksesibilitas -->
    <script src="{{ asset('js/accessibility.js') }}"></script>
</head>
<body>
    <!-- Header with logo and auth buttons -->
    <header>
        <div class="logo">
            <img src="{{ asset('Assets/logo hd.png') }}" alt="InkluSwift Logo" onerror="this.src='{{ asset('Assets/default-logo.png') }}'">
            <h1>InkluSwift</h1>
        </div>

        <div class="auth-buttons">
            <a href="{{ route('cart') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/cart.png') }}" alt="Cart" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiNGRjRCM0EiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNyA0VjJhMSAxIDAgMCAwLTItdjJIOGEuNS41IDAgMCAwIC41LS41VjRoN3YuNWEuNS41IDAgMCAwIC41LjVIOGEuNS41IDAgMCAwLS41LS41VjRIN3ptMTMgNUgxN2wuNSAySDUuNWwtLjUtMkg0YTEgMSAwIDEgMCAwIDJoMTJhMSAxIDAgMSAwIDAtMmgtMnptLTkgN2ExIDEgMCAxIDEgMi0yIDEgMSAwIDAgMS0yIDJ6bTcgMGExIDEgMCAxIDEgMi0yIDEgMSAwIDAgMS0yIDJ6Ii8+PC9zdmc+'">
                Keranjang
            </a>
            <a href="{{ route('edit-profile') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/profile.png') }}" alt="Profile" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiNGRjRCM0EiIHZpZXdCb3g9IjAgMCAyNCAyNCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTIgMTJjMi4yMSAwIDQtMS43OSA0LTRzLTEuNzktNC00LTQtNCAxLjc5LTQgNCAxLjc5IDQgNCA0em0wIDJjLTIuNjcgMC04IDEuMzQtOCA0djJoMTZ2LTJjMC0yLjY2LTUuMzMtNC04LTR6Ii8+PC9zdmc+'">
                Profil
            </a>
        </div>
    </header>

    <!-- Hero section with search bar -->
    <section class="hero">
        <div class="hero-content">
            <h1>Mau makan apa hari ini?</h1>
            <p>Pesan makanan favoritmu di sini!</p>
            <form action="{{ route('home.search') }}" method="GET" class="search-bar">
                <input type="text" name="q" placeholder="Cari Makanan" value="{{ $query ?? '' }}">
                <button type="submit">Cari</button>
            </form>
        </div>
        <img src="{{ asset('Assets/mam.png') }}" alt="Food display" class="hero-img" onerror="this.src='{{ asset('Assets/default-hero.png') }}'">
    </section>

    <!-- Categories section -->
    <section class="categories">
        <h2>Cari berdasarkan Kategori</h2>
        <div class="category-list">
            <a href="{{ route('home.category', 'foods') }}" class="category">
                <div class="category-img">
                    <img src="{{ asset('Assets/makanan.png') }}" alt="Makanan" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgZmlsbD0iI0ZGNEIzQSIgdmlld0JveD0iMCAwIDI0IDI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xOC4wNiAxMi45NGMtLjUxLS42Ni0xLjI2LS45NC0xLjk0LS45NGgtNGMuMjgtLjcyIDEuMi0yIDEuMi0ycy0xLjQtMS0zLjItMWMtMS4yIDAtMi4yLjgtMi4yIDJoNC4ydjJIOWMtMS4xIDAtMiAuOS0yIDJ2NGMwIDEuMS45IDIgMiAyaDh2LTJIOXYtNGg4YzEuMSAwIDItLjkgMi0ydjFsLS45NCAwem0tOC4wNi0uOTRIMWMtMS4xIDAtMiAuOS0yIDJ2NGMwIDEuMS45IDIgMiAyaDl2LTJIMXYtNGg5di0yeiIvPjwvc3ZnPg=='">
                </div>
                <h3>Makanan</h3>
            </a>
            <a href="{{ route('home.category', 'drinks') }}" class="category">
                <div class="category-img">
                    <img src="{{ asset('Assets/minuman.jpg') }}" alt="Minuman" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgZmlsbD0iI0ZGNEIzQSIgdmlld0JveD0iMCAwIDI0IDI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik05IDE5YzAgMS4xLjkgMiAyIDJzMi0uOS0yLTItMi0uOS0yLTJ2LTFIOWMtMS4xIDAtMiAuOS0yIDJ2MmMwIDEuMS45IDIgMiAyaDZjMS4xIDAgMi0uOS0yLTJ2LTJjMC0xLjEtLjktMi0yLTJIOWMtMS4xIDAtMiAuOS0yIDJ2MXptMy05YzAtMS4xLS45LTItMi0ycy0yIC45LTIgMnYxaDJjMS4xIDAgMi0uOS0yLTJWOXoiLz48L3N2Zz4='">
                </div>
                <h3>Minuman</h3>
            </a>
            <a href="{{ route('home.category', 'snacks') }}" class="category">
                <div class="category-img">
                    <img src="{{ asset('Assets/camilan.png') }}" alt="Camilan" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgZmlsbD0iI0ZGNEIzQSIgdmlld0JveD0iMCAwIDI0IDI0IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik0xMiAyQzYuNDggMiAyIDYuNDggMiAxMnM0LjQ4IDEwIDEwIDEwIDEwLTQuNDggMTAtMTBTMTcuNTIgMiAxMiAyem0tMiAxNWwtNS01IDEuNDEtMS40MUwxMCAxNC4xN2w3LjU5LTcuNTlMMTkgOGwtOSA5eiIvPjwvc3ZnPg=='">
                </div>
                <h3>Camilan</h3>
            </a>
        </div>
    </section>

    <!-- Food Items Section -->
    <section class="food-items">
        <h2>
            @if(isset($category))
                Daftar {{ ucfirst($category == 'foods' ? 'Makanan' : ($category == 'drinks' ? 'Minuman' : 'Camilan')) }}
            @elseif(isset($query))
                Hasil Pencarian: "{{ $query }}"
            @else
                Daftar Menu
            @endif
        </h2>
        
        @if($menus->count() > 0)
            <div class="food-grid">
                @foreach($menus as $menu)
                    <a href="{{ route('description', ['menu_id' => $menu->menu_id]) }}" class="food-card-link">
                        <div class="food-card">
                            <img src="{{ $menu->image ? asset('storage/' . $menu->image) : asset('Assets/default-food.png') }}" 
                                 alt="{{ $menu->menu_name }}" 
                                 class="food-img"
                                 onerror="this.src='{{ asset('Assets/default-food.png') }}'; this.onerror=null;"
                                 loading="lazy">
                            <div class="food-info">
                                <h4 class="food-name">{{ $menu->menu_name }}</h4>
                                <div class="price-container">
                                    @if($menu->discount > 0)
                                        @php
                                            $discountedPrice = $menu->price - ($menu->price * $menu->discount / 100);
                                        @endphp
                                        <p class="food-price discounted">Rp{{ number_format($discountedPrice, 0, ',', '.') }}</p>
                                        <p class="food-original-price">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                                        <span class="discount-badge">{{ $menu->discount }}% OFF</span>
                                    @else
                                        <p class="food-price">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                                <!-- @if($menu->category)
                                    <p class="food-category">{{ ucfirst($menu->category == 'foods' ? 'Makanan' : ($menu->category == 'drinks' ? 'Minuman' : 'Camilan')) }}</p>
                                @endif -->
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-results">
                <p>
                    @if(isset($query))
                        Tidak ada hasil untuk pencarian "{{ $query }}"
                    @elseif(isset($category))
                        Belum ada menu untuk kategori ini
                    @else
                        Belum ada menu tersedia
                    @endif
                </p>
                @if(isset($query) || isset($category))
                    <a href="{{ route('home') }}" class="btn btn-primary">Lihat Semua Menu</a>
                @endif
            </div>
        @endif
    </section>

    <!-- Footer -->
    <footer>
        <p>All Rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>