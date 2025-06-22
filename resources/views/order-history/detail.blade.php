<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->order_id }} - InkluSwift</title>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
    <link rel="stylesheet" href="{{asset('css/order-history.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
</head>
<body>
    <!-- Header with logo and auth buttons -->
    <header>
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
        </div>
        <div class="auth-buttons">
            <a href="{{route('cart')}}" class="btn btn-primary">
                <img src="{{asset('Assets/cart.png')}}" alt="Cart">
                Keranjang</a>
            <a href="{{route('edit-profile')}}" class="btn btn-primary">
                <img src="{{asset('Assets/profile.png')}}" alt="Profile">
                Profil</a>
        </div>
    </header>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Content -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-header">
                <div class="profile-pic">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <span>{{ $user->name }}</span>
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
                <a href="{{route('order-history.index')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    History Pemesanan
                </a>
            </div>
            <form action="{{route('logout')}}" method="POST" style="margin-top: 8rem;">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="content-header">
                <h2>Detail Pesanan #{{ $order->order_id }}</h2>
                <p>Informasi lengkap pesanan Anda</p>
                <div class="back-button">
                    <a href="{{route('order-history.index')}}" class="btn-action btn-detail">
                        <i class="fas fa-arrow-left"></i> Kembali ke Histori
                    </a>
                </div>
            </div>

            <!-- Order Detail -->
            <div class="order-detail">
                <div class="form-group">
                    <div class="detail-header">
                        <div class="order-info">
                            <h3>Pesanan #{{ $order->order_id }}</h3>
                            <p class="order-date">
                                <i class="fas fa-calendar"></i>
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Status Pesanan</label>
                    </div>
                    <div class="order-timeline">
                        <div class="timeline">
                            <div class="timeline-item {{ $order->status == 'pending' || $order->status == 'confirmed' || $order->status == 'preparing' || $order->status == 'ready' || $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Pesanan Dibuat</h4>
                                    <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status == 'confirmed' || $order->status == 'preparing' || $order->status == 'ready' || $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Pesanan Dikonfirmasi</h4>
                                    @if($order->status != 'pending')
                                        <p>{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status == 'preparing' || $order->status == 'ready' || $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Sedang Diproses</h4>
                                    @if($order->status == 'preparing' || $order->status == 'ready' || $order->status == 'completed')
                                        <p>{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status == 'ready' || $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Pesanan Siap</h4>
                                    @if($order->status == 'ready' || $order->status == 'completed')
                                        <p>{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="timeline-item {{ $order->status == 'completed' ? 'active' : '' }}">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h4>Pesanan Selesai</h4>
                                    @if($order->status == 'completed')
                                        <p>{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Item Pesanan</label>
                    </div>
                    <div class="order-items-detail">
                        <div class="items-list">
                            @foreach($order->orderItems as $item)
                                <div class="item-detail">
                                    <div class="item-info">
                                        <h4>{{ $item->menu_name }}</h4>
                                        @if($item->notes)
                                            <p class="item-notes">Catatan: {{ $item->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="item-quantity">
                                        <span>x{{ $item->quantity }}</span>
                                    </div>
                                    <div class="item-price">
                                        @if($item->discount_percent > 0)
                                            <span class="original-price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                            <span class="discounted-price">Rp {{ number_format($item->price * (1 - $item->discount_percent/100), 0, ',', '.') }}</span>
                                        @else
                                            <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <div class="item-total">
                                        <strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Ringkasan Pesanan</label>
                    </div>
                    <div class="order-summary">
                        <div class="summary-details">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="summary-row">
                                    <span>Diskon</span>
                                    <span class="discount">-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($order->service_charge > 0)
                                <div class="summary-row">
                                    <span>Biaya Ongkir</span>
                                    <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="summary-row total">
                                <span><strong>Total</strong></span>
                                <span><strong>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                Payment Information
                @if($order->payment)
                    <div class="form-group">
                        <div class="form-header">
                            <label class="form-label">Informasi Pembayaran</label>
                        </div>
                        <div class="payment-info">
                            <div class="payment-details">
                                <div class="payment-row">
                                    <span>Metode Pembayaran</span>
                                    <span>{{ ucfirst($order->payment->method_name) }}</span>
                                </div>
                                <!-- <div class="payment-row">
                                    <span>Status Pembayaran</span>
                                    <span class="payment-status status-{{ $order->payment->status }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>
                                @if($order->payment->paid_at)
                                    <div class="payment-row">
                                        <span>Tanggal Pembayaran</span>
                                        <span>{{ $order->payment->paid_at->format('d M Y, H:i') }}</span>
                                    </div>
                                @endif -->
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Actions -->
                <div class="form-group">
                    <div class="order-actions-detail">
                        @if($order->canBeCancelled())
                            <form method="POST" action="{{ route('order-history.cancel', $order->order_id) }}" class="cancel-form" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    <i class="fas fa-times"></i> Batalkan Pesanan
                                </button>
                            </form>
                        @endif

                        @if($order->status == 'completed')
                            <form method="POST" action="{{ route('order-history.reorder', $order->order_id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-reorder">
                                    <i class="fas fa-redo"></i> Pesan Lagi
                                </button>
                            </form>
                        @endif

                        <button onclick="window.print()" class="btn-action btn-print">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>

    <!-- JavaScript -->
    <script src="{{asset('js/order-history.js')}}"></script>
</body>
</html>