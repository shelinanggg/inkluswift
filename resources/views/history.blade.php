<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift History Orders</title>
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
            font-family: Arial, sans-serif;
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
            background-color: #fff;
        }

        .logo {
            display: flex;
            align-items: center;
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
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: flex;
            gap: 2rem;
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            background-color: #ff7070;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 5px;
            cursor: pointer;
            color: #666;
            text-decoration: none;
        }
        
        .menu-item.active {
            color: #ff5045;
        }
        
        .menu-item svg {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }
        
        .logout-btn {
            display: flex;
            align-items: center;
            margin-top: 8rem;
            padding: 0.75rem;
            border-radius: 5px;
            cursor: pointer;
            color: #666;
            background-color: white;
            border: 1px solid #f0f0f0;
            width: 100%;
            font-size: 14px;
        }
        
        .logout-btn svg {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }
        
        .content {
            flex: 1;
        }
        
        .content-header {
            margin-bottom: 1.5rem;
        }
        
        .content-header h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .filter-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .tab {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.875rem;
            border: 1px solid #ddd;
            background-color: white;
        }
        
        .tab.active {
            background-color: #ff5045;
            color: white;
            border-color: #ff5045;
        }
        
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .order-card {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .order-date {
            font-weight: bold;
            color: #333;
        }
        
        .payment-info {
            display: flex;
            align-items: center;
        }
        
        .payment-icon {
            margin-right: 0.5rem;
        }
        
        .payment-method {
            color: #666;
            font-size: 0.875rem;
        }
        
        .order-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-items {
            display: flex;
            align-items: center;
        }
        
        .order-item {
            width: 50px;
            height: 50px;
            margin-right: 0.5rem;
        }
        
        .item-more {
            font-size: 0.875rem;
            color: #666;
            margin-left: 0.5rem;
        }
        
        .order-details {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .order-quantity {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .quantity-label {
            color: #666;
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }
        
        .quantity-value {
            font-weight: bold;
            color: #333;
        }
        
        .order-status {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        
        .status-processing {
            background-color: #fff1f0;
            color: #ff5045;
            border: 1px solid #ff5045;
        }
        
        .status-completed {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #2e7d32;
        }
        
        .status-cancelled {
            background-color: #f5f5f5;
            color: #red;
            border: 1px solid #757575;
        }
        
        .view-details {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }
        
        .view-details svg {
            margin-left: 0.5rem;
            width: 16px;
            height: 16px;
        }
        
        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
        }
        
        .orange-icon {
            color: #ff7730;
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


    <!-- Main Content -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-header">
                <div class="profile-pic">M</div>
                <span>Mario</span>
            </div>
            <div class="menu-items">
                <a href="{{route('edit-profile')}}" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 20C18 16.6863 15.3137 14 12 14C8.68629 14 6 16.6863 6 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Profil Saya
                </a>
                <a href="{{route('change-password')}}" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9S13.1 11 12 11 10 10.1 10 9 10.9 7 12 7ZM18 11C18 15.1 15.64 18.78 12 19.5C8.36 18.78 6 15.1 6 11V6.3L12 3.18L18 6.3V11Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Ganti Password
                </a>
                <a href="{{route('history')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    History Pemesanan
                </a>
            </div>
            <button class="logout-btn" onclick="window.location.href='{{route('cart')}}'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </button>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="content-header">
                <h2>History Pemesanan</h2>
                <div class="filter-tabs">
                    <button class="tab active">Semua</button>
                    <button class="tab">Dalam Proses</button>
                    <button class="tab">Selesai</button>
                    <button class="tab">Dibatalkan</button>
                </div>
            </div>
            <div class="order-list">
                <!-- Order 1 -->
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-date">Apr 5, 2022, 10:07 AM</div>
                        <div class="payment-info">
                            <div class="payment-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 10H21M7 15H8M12 15H13M6 19H18C19.6569 19 21 17.6569 21 16V8C21 6.34315 19.6569 5 18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="payment-method">
                                <div>Rp111.000</div> 
                                <div>Dibayar dengan kartu</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-content">
                        <div class="order-items">
                            <div class="order-item">
                                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="20" fill="#f8f8f8" stroke="#eee" stroke-width="1"/>
                                    <g transform="translate(10, 15)">
                                        <circle cx="15" cy="10" r="8" fill="#ff9800"/>
                                        <circle cx="12" cy="7" r="1" fill="#ffcc80"/>
                                        <path d="M8,12 C10,8 20,8 22,12" stroke="#4caf50" stroke-width="2" fill="none"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="order-item">
                                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="20" fill="#f8f8f8" stroke="#eee" stroke-width="1"/>
                                    <g transform="translate(10, 15)">
                                        <circle cx="15" cy="10" r="8" fill="#ff9800"/>
                                        <circle cx="12" cy="7" r="1" fill="#ffcc80"/>
                                        <path d="M8,12 C10,8 20,8 22,12" stroke="#4caf50" stroke-width="2" fill="none"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="order-item">
                                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="20" fill="#f8f8f8" stroke="#eee" stroke-width="1"/>
                                    <g transform="translate(10, 15)">
                                        <circle cx="15" cy="10" r="8" fill="#ff9800"/>
                                        <circle cx="12" cy="7" r="1" fill="#ffcc80"/>
                                        <path d="M8,12 C10,8 20,8 22,12" stroke="#4caf50" stroke-width="2" fill="none"/>
                                    </g>
                                </svg>
                            </div>
                            <div class="item-more">+1</div>
                        </div>
                        <div class="order-details">
                            <div class="order-quantity">
                                <div class="quantity-label">Jumlah Item</div>
                                <div class="quantity-value">4x</div>
                            </div>
                            <div class="order-status status-processing">Dalam Proses</div>
                            <a href="#" class="view-details">
                                Lihat Detail
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Order 2 -->
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-date">Apr 2, 2022, 10:07 AM</div>
                        <div class="payment-info">
                            <div class="payment-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 10H21M7 15H8M12 15H13M6 19H18C19.6569 19 21 17.6569 21 16V8C21 6.34315 19.6569 5 18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="payment-method">
                                <div>Rp25.000</div> 
                                <div>Dibayar dengan kartu</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-content">
                        <div class="order-items">
                            <div class="order-item">
                                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="20" fill="#f8f8f8" stroke="#eee" stroke-width="1"/>
                                    <g transform="translate(10, 15)">
                                        <circle cx="15" cy="10" r="8" fill="#ff9800"/>
                                        <circle cx="12" cy="7" r="1" fill="#ffcc80"/>
                                        <path d="M8,12 C10,8 20,8 22,12" stroke="#4caf50" stroke-width="2" fill="none"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="order-quantity">
                                <div class="quantity-label">Jumlah Item</div>
                                <div class="quantity-value">1x</div>
                            </div>
                            <div class="order-status status-completed">Selesai</div>
                            <a href="#" class="view-details">
                                Lihat Detail
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Order 3 -->
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-date">Apr 2, 2022, 10:07 AM</div>
                        <div class="payment-info">
                            <div class="payment-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 10H21M7 15H8M12 15H13M6 19H18C19.6569 19 21 17.6569 21 16V8C21 6.34315 19.6569 5 18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19Z" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="payment-method">
                                <div>Rp25.000</div> 
                                <div>Dibayar dengan kartu</div>
                            </div>
                        </div>
                    </div>
                    <div class="order-content">
                        <div class="order-items">
                            <div class="order-item">
                                <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="25" cy="25" r="20" fill="#f8f8f8" stroke="#eee" stroke-width="1"/>
                                    <g transform="translate(10, 15)">
                                        <circle cx="15" cy="10" r="8" fill="#ff9800"/>
                                        <circle cx="12" cy="7" r="1" fill="#ffcc80"/>
                                        <path d="M8,12 C10,8 20,8 22,12" stroke="#4caf50" stroke-width="2" fill="none"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="order-details">
                            <div class="order-quantity">
                                <div class="quantity-label">Jumlah Item</div>
                                <div class="quantity-value">1x</div>
                            </div>
                            <div class="order-status status-cancelled">Dibatalkan</div>
                            <a href="#" class="view-details">
                                Lihat Detail
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 5L16 12L9 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>All rights Reserved © 2025, InkluSwift</p>
</footer>
</body>
</html>