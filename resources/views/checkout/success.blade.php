<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - InkluSwift</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            align-items: center;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background-color: white;
            color: #FF4B3A;
            border: 2px solid #FF4B3A;
        }

        .btn-secondary {
            background-color: #FF4B3A;
            color: white;
            border: 2px solid #FF4B3A;
        }

        .btn-outline {
            background-color: transparent;
            color: #FF4B3A;
            border: 1px solid #FF4B3A;
        }
        
        .btn img {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }

        .btn:hover {
            opacity: 0.8;
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
        
        /* Main Content */
        .main-content {
            padding: 20px 10%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .success-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            margin-bottom: 20px;
        }

        .success-icon i {
            color: #4CAF50;
            font-size: 60px;
        }

        .success-container h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .success-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        /* Content Row Layout */
        .content-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .order-details {
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

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .info-row span:first-child {
            color: #666;
        }

        .info-row span:last-child {
            font-weight: bold;
            color: #333;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-confirmed {
            color: #4CAF50;
        }

        .status-pending {
            color: #FF9800;
        }

        .total-amount {
            color: #FF4B3A;
            font-size: 16px;
        }

        .customer-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .customer-info h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .customer-info p {
            margin-bottom: 8px;
            font-size: 14px;
            color: #666;
        }

        .customer-info strong {
            color: #333;
        }

        /* Order Items */
        .order-items {
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-weight: bold;
            color: #333;
        }

        .item-qty {
            color: #666;
        }

        .item-price {
            font-weight: bold;
            color: #FF4B3A;
        }

        /* Payment Info */
        .payment-info {
            background-color: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid #FF9800;
        }

        .payment-info h4 {
            color: #FF9800;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .payment-info p {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .payment-details {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .payment-details strong {
            color: #333;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 12px 20px;
            font-size: 14px;
        }

        .action-buttons .btn i {
            margin-right: 8px;
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

        /* Responsive */
        @media (max-width: 768px) {
            .content-row {
                flex-direction: column;
            }

            .main-content {
                padding: 20px 5%;
            }

            header {
                padding: 8px 5%;
            }

            nav {
                padding: 15px 20px;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="logo">
            <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>
        <div class="auth-buttons">
            <a href="{{ route('cart') }}" class="btn btn-primary">
                <img src="{{asset('Assets/cart.png')}}" alt="Cart">
                Keranjang (0)
            </a>
            <a href="{{ route('profile') }}" class="btn btn-primary">
                <img src="{{asset('Assets/profile.png')}}" alt="Profile">
                Profil
            </a>
        </div>
    </header>

    <!-- Navigasi -->
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Beranda</a></li>
            <!-- <li><a href="{{ route('description') }}">Menu</a></li> -->
            <!-- <li><a href="{{ route('cart') }}">Keranjang</a></li>
            <li><a href="{{ route('checkout') }}">Checkout</a></li>
            <li><a href="{{ route('status') }}">Status Pesanan</a></li> -->
        </ul>
    </nav>

    <!-- Konten Utama -->
    <div class="main-content">
        <!-- Success Message -->
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Pesanan Berhasil Dibuat!</h1>
            <p class="success-message">
                Terima kasih! Pesanan Anda telah berhasil dibuat dengan ID:
                <strong>{{ $order->order_id }}</strong>
            </p>
        </div>

        <!-- Content Row -->
        <div class="content-row">
            <!-- Order Details -->
            <div class="order-details">
                <h3 class="section-title">Detail Pesanan</h3>

                <div class="info-row">
                    <span>ID Pesanan:</span>
                    <span>{{ $order->order_id }}</span>
                </div>
                <div class="info-row">
                    <span>Tanggal Pesanan:</span>
                    <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span>Status:</span>
                    <span class="status status-{{ $order->status }}">
                        @if($order->status == 'confirmed')
                            <i class="fas fa-check"></i> Dikonfirmasi
                        @elseif($order->status == 'pending')
                            <i class="fas fa-clock"></i> Menunggu Konfirmasi
                        @else
                            {{ ucfirst($order->status) }}
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span>Metode Pembayaran:</span>
                    <span>{{ $order->payment->method_name }}</span>
                </div>

                <!-- Customer Info -->
                <div class="customer-info">
                    <h4>Informasi Pelanggan</h4>
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                    @if($order->notes)
                        <p><strong>Catatan:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="section-title">Ringkasan Pembayaran</h3>
                <div class="info-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span>
                </div>
                <div class="info-row total-amount">
                    <span>Total harga</span>
                    <span>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-items">
            <div class="items-header">
                <span>Menu yang Dipesan</span>
                <span>Jumlah</span>
            </div>
            @foreach($order->orderItems as $item)
                <div class="order-item">
                    <div>
                        <div class="item-name">{{ $item->menu_name }}</div>
                        <div class="item-price">Rp {{ number_format($item->subtotal_after_discount, 0, ',', '.') }}</div>
                    </div>
                    <div class="item-qty">{{ $item->quantity }}×</div>
                </div>
            @endforeach
        </div>

        <!-- Payment Info -->
        @if($order->status == 'pending')
            <div class="payment-info">
                <h4><i class="fas fa-info-circle"></i> Informasi Pembayaran</h4>
                <p>Pesanan Anda sedang menunggu konfirmasi pembayaran. Kami akan memproses pesanan setelah pembayaran dikonfirmasi.</p>

                @if($order->payment->destination_account)
                    <div class="payment-details">
                        <p><strong>Transfer ke:</strong></p>
                        <p>{{ $order->payment->destination_account }}</p>
                    </div>
                @endif

                @if($order->proof_image)
                    <p><i class="fas fa-check"></i> Bukti pembayaran telah diunggah</p>
                @endif
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('cart') }}" class="btn btn-secondary">
                <i class="fas fa-shopping-cart"></i> Belanja Lagi
            </a>
            <button onclick="window.print()" class="btn btn-outline">
                <i class="fas fa-print"></i> Cetak Pesanan
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartCount = document.querySelector('.auth-buttons .btn span');
            if (cartCount) {
                cartCount.textContent = '0';
            }
        });
    </script>
</body>
</html>