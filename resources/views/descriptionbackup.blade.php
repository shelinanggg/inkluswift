<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Menu Description</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman Anda -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #eee;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 8%;
            background-color: #eee;
        }

        .logo {
            display: flex;
            align-items: center;
            color: black;
        }

        .logo img {
            height: 77px;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: flex;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: white;
            color: #FF4B3A;
            border: 2px solid #FF4B3A;
        }
        
        .btn-primary img {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }
        
        nav {
            padding: 15px 40px;
            background-color: white;
            border-bottom: 1px solid #ddd;
        }
        
        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
        }
        
        nav a {
            text-decoration: none;
            color: #666;
        }
        
        nav a.active {
            color: #ff4d4d;
            font-weight: bold;
        }
        
        .product-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: flex;
            gap: 40px;
        }
        
        .product-image {
            flex: 1;
            max-width: 400px;
        }
        
        .product-image img {
            width: 100%;
            border-radius: 10px;
        }
        
        .product-details {
            flex: 1;
        }
        
        .product-title {
            font-size: 36px;
            margin-bottom: 15px;
            color: #222;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .current-price {
            font-size: 24px;
            font-weight: bold;
            color: #222;
        }
        
        .original-price {
            font-size: 18px;
            color: #999;
            text-decoration: line-through;
            margin-left: 10px;
        }
        
        .add-to-cart {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 40px;
        }
        
        .info-section {
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            cursor: pointer;
        }
        
        .info-header h3 {
            font-size: 18px;
            color: #222;
        }
        
        .info-content {
            line-height: 1.6;
            color: #555;
        }
        
        .chevron {
            width: 20px;
            height: 20px;
            transition: transform 0.3s ease;
        }
        
        .chevron.down {
            transform: rotate(180deg);
        }
        
        .hidden {
            display: none;
        }
        
        footer {
            background-color: #222;
            color: white;
            padding: 20px 40px;
            text-align: center;
            font-size: 14px;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    
    <header>
        <div class="logo">
            <img src="/Assets/logo hd.png" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>
        <div class="auth-buttons">
            <a href="#" class="btn btn-primary">
                <img src="{{asset('Assets/cart.png')}}" alt="Cart">
                Keranjang</a>
            <a href="#" class="btn btn-primary">
                <img src="{{asset('Assets/profile.png')}}" alt="Profile">
                Profil</a>
        </div>
    </header>
    
    <nav>
        <ul>
            <li><a href="{{route('home')}}">Beranda</a></li>
            <li><a href="{{route('description')}}" class="active">Deskripsi</a></li>
        </ul>
    </nav>
    
    <main class="product-container">
        <div class="product-image">
            <img id="foodImage" src="" alt="foodImage">
        </div>
        
        <div class="product-details">
            <h1 class="product-title" id="product-title"></h1>
            
            <div class="product-price">
                <span class="current-price" id="current-price"></span>
                <span class="original-price" id="original-price"></span>
            </div>
            
            <button class="add-to-cart" id="add-to-cart-btn">Tambahkan ke keranjang</button>
            
            <div class="info-section">
                <div class="info-header" onclick="toggleSection('deskripsi')">
                    <h3>Deskripsi</h3>
                    <img src="Assets/chevron-up.svg" alt="Toggle" class="chevron up" id="deskripsi-chevron">
                </div>
                <div class="info-content" id="deskripsi-content">
                    <p id="description"></p>
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-header" onclick="toggleSection('bahan')">
                    <h3>Bahan</h3>
                    <img src="{{asset('Assets/chevron-up.svg')}}" alt="Toggle" class="chevron up" id="bahan-chevron">
                </div>
                <div class="info-content" id="bahan-content">
                    <p id="ingredients"></p>
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-header" onclick="toggleSection('penyimpanan')">
                    <h3>Penyimpanan</h3>
                    <img src="{{asset('Assets/chevron-up.svg')}}" alt="Toggle" class="chevron up" id="penyimpanan-chevron">
                </div>
                <div class="info-content" id="penyimpanan-content">
                    <p id="storage"></p>
                </div>
            </div>
        </div>
    </main>
    
    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <script>
        function toggleSection(id) {
            const content = document.getElementById(`${id}-content`);
            const chevron = document.getElementById(`${id}-chevron`);
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.classList.remove('down');
            } else {
                content.classList.add('hidden');
                chevron.classList.add('down');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const foodData = JSON.parse(localStorage.getItem('foodData'));

            if (foodData) {
                document.getElementById('product-title').textContent = foodData.title;
                document.getElementById('current-price').textContent = foodData.price;
                document.getElementById('original-price').textContent = foodData.originalPrice;
                document.getElementById('description').textContent = foodData.description;
                document.getElementById('ingredients').textContent = foodData.ingredients;
                document.getElementById('storage').textContent = foodData.storage;
                document.getElementById('foodImage').src = foodData.image;
                document.getElementById('foodImage').alt = foodData.title;
            }

            document.getElementById('add-to-cart-btn').addEventListener('click', function() {
                // Simpan data ke keranjang kalau perlu, lalu redirect
                window.location.href = "{{route('cart')}}";
            });
        });
    </script>
</body>
</html>
