<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - InkluSwift</title>
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
            flex-direction: column;
            gap: 20px;
        }
        
        .content-row {
            display: flex;
            gap: 20px;
        }
        
        .status-container {
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
        
        .status-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        
        .status-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .status-icon {
            width: 60px;
            height: 60px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .status-check {
            color: white;
            font-size: 30px;
        }
        
        .status-message {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        
        .status-bar {
            display: flex;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .status-step {
            flex: 1;
            height: 6px;
            border-radius: 3px;
        }
        
        .step-active {
            background-color: #ff5a46;
        }
        
        .step-inactive {
            background-color: #e0e0e0;
            margin-left: 5px;
        }
        
        .status-date {
            font-size: 14px;
            color: #666;
        }
        
        .payment-info, .address-info {
            margin-top: 20px;
        }
        
        .info-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .card-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-icon {
            width: 20px;
            height: 20px;
            background-color: #ff5a46;
            border-radius: 3px;
        }
        
        .card-number {
            color: #ff5a46;
            font-size: 14px;
        }
        
        .address-row {
            font-size: 14px;
        }
        
        .location-icon {
            color: #ff5a46;
            font-size: 20px;
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
        
        .order-items {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .item-details {
            display: flex;
            align-items: center;
        }
        
        .item-image {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }
        
        .item-name {
            font-weight: bold;
        }
        
        .item-price {
            color: #666;
            font-size: 14px;
        }
        
        .item-quantity {
            text-align: right;
            font-weight: bold;
        }
        
        .cancel-container {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .cancel-text {
            font-size: 14px;
            color: #666;
        }
        
        .cancel-button {
            background-color: white;
            color: #ff5a46;
            border: 1px solid #ff5a46;
            border-radius: 20px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
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
            <li><a href="{{route('checkout')}}">Checkout</a></li>
            <li><a href="{{route('status')}}" class="active">Status Pesanan</a></li>
        </ul>
    </nav>
    
    <div class="main-content">
        <div class="content-row">
            <div class="status-container">
                <h2 class="status-title">Status Pesanan</h2>
                <div class="status-content">
                    <div class="status-icon">
                        <span class="status-check">✓</span>
                    </div>
                    <div class="status-message">Pesanan anda diterima oleh resto</div>
                    <div class="status-bar">
                        <div class="status-step step-active"></div>
                        <div class="status-step step-inactive"></div>
                        <div class="status-step step-inactive"></div>
                    </div>
                    <div class="status-date">Apr 5, 2022, 10:07 AM</div>
                </div>
            </div>
            
            <div class="order-summary">
                <h3 class="summary-title">Detail Pesanan</h3>
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
                
                <div class="payment-info">
                    <div class="info-title">Dibayar Dengan</div>
                    <div class="card-info">
                        <span class="card-number">MasterCard ***3434</span>
                    </div>
                </div>
                
                <div class="address-info">
                    <div class="info-title">Alamat Pengiriman</div>
                    <div class="address-row">
                        <span>Lokasi Anda</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="order-items">
            <div class="items-header">
                <span>Menu yang Dipesan</span>
                <span>Jumlah</span>
            </div>
            
            <div class="item-row">
                <div class="item-details">
                    <img src="{{asset('Assets/cheese burger.jpg')}}" alt="Cheese Burger" class="item-image">
                    <div>
                        <div class="item-name">Cheese Burger</div>
                        <div class="item-price">Rp35.000</div>
                    </div>
                </div>
                <div class="item-quantity">1×</div>
            </div>
        </div>
        
        <div class="cancel-container">
            <div class="cancel-text">Anda dapat membatalkan pesanan Anda sebelum mulai disiapkan.</div>
            <button class="cancel-button">Batalkan Pesanan</button>
        </div>
    </div>

    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>
</body>
</html>