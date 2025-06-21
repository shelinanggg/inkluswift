<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Checkout</title>
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
        
        /* Header styles */
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
        
        /* Navigation */
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
        
        .main-content {
            padding: 20px 10%;
            display: flex;
            gap: 20px;
        }
        
        .checkout-container {
            flex: 2;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .order-summary {
            flex: 1;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }
        
        .checkout-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .checkout-section {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .info-icon {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        
        .chevron-icon {
            color: #888;
        }
        
        .delivery-address {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 14px;
        }
        
        .address-label {
            color: #999;
        }
        
        .location-icon {
            color: #ff5a46;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 14px;
        }
        
        .card-info {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .card-icon {
            color: #ff5a46;
        }
        
        .card-number {
            color: #ff5a46;
            font-size: 14px;
        }
        
        .card-hidden {
            color: #999;
        }
        
        .ordered-items {
            display: flex;
            align-items: center;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }
        
        .item-icon {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }
        
        .more-items {
            color: #666;
            font-size: 14px;
        }
        
        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin: 20px 0;
        }
        
        .checkout-button {
            background-color: #ff5a46;
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Footer */
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
    <!-- Header with logo and auth buttons -->
    <header>
        <div class="logo">
            <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>
        <div class="auth-buttons">
            <a href="{{route('cart')}}" class="btn btn-primary">
                <img src="{{asset('Assets/cart.png')}}" alt="Cart">
                Keranjang</a>
            <a href="{{route('profile')}}" class="btn btn-primary">
                <img src="{{asset('Assets/profile.png')}}" alt="Profile">
                Profil</a>
        </div>
    </header>
    
    <nav>
        <ul>
            <li><a href="{{route('home')}}">Beranda</a></li>
            <li><a href="{{route('description')}}">Menu</a></li>
            <li><a href="{{route('cart')}}">Keranjang</a></li>
            <li><a href="{{route('checkout')}}" class="active">Checkout</a></li>
        </ul>
    </nav>
    
    <div class="main-content">
        <div class="checkout-container">
            <h2 class="checkout-title">Checkout</h2>
            
            <div class="checkout-section">
                <div class="section-header">
                    <div class="section-title">
                        Informasi Pengiriman <span class="info-icon">i</span>
                    </div>
                    <div class="chevron-icon">›</div>
                </div>
                <div class="delivery-address">
                    <span class="address-label">Diantar ke</span>
                    <span>Lokasi Anda</span>
                </div>
            </div>
            
            <div class="checkout-section">
                <div class="section-header">
                    <div class="section-title">
                        Metode Pembayaran <span class="info-icon">i</span>
                    </div>
                    <div class="chevron-icon">›</div>
                </div>
                <div class="payment-method">
                    <span class="address-label">Dibayar dengan</span>
                    <div class="card-info">
                        <span class="card-number">Mastercard</span>
                        <span class="card-hidden">***</span>
                        <span class="card-number">3434</span>
                    </div>
                </div>
            </div>
            
            <div class="checkout-section">
                <div class="section-header">
                    <div class="section-title">
                        Menu yang Dipesan <span class="info-icon">i</span>
                    </div>
                    <div class="chevron-icon">›</div>
                </div>
                <div class="ordered-items">
                    <img src="{{asset('Assets/cheese burger.jpg')}}" alt="Item 1" class="item-icon">
                </div>
            </div>
        </div>
        
        <div class="order-summary">
            <h3 class="summary-title">Detail Pemesanan</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp35.000</span>
            </div>
            <div class="summary-row">
                <span>Ongkos kirim</span>
                <span>Rp9.000</span>
            </div>
            <div class="summary-row">
                <span>Biaya aplikasi</span>
                <span>Rp1.000</span>
            </div>
            <div class="summary-total">
                <span>Total harga</span>
                <span>Rp45.000</span>
            </div>
            <button class="checkout-button" onclick="window.location.href='{{route('status')}}'">Proses Pesanan</button>
        </div>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>
</body>
</html>