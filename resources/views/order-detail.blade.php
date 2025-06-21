@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_id)

@section('content')
<div class="order-detail-container">
    <!-- Header Section -->
    <div class="detail-header">
        <div class="header-left">
            <a href="{{ route('order.history') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Riwayat
            </a>
            <h1 class="page-title">Detail Pesanan</h1>
            <p class="order-id">#{{ $order->order_id }}</p>
        </div>
        <div class="header-right">
            <div class="order-status status-{{ $order->status }}">
                <i class="fas fa-circle"></i>
                {{ $order->status_label }}
            </div>
        </div>
    </div>

    <div class="detail-content">
        <!-- Order Information Card -->
        <div class="info-card">
            <h2 class="card-title">
                <i class="fas fa-info-circle"></i>
                Informasi Pesanan
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Tanggal Pesanan:</label>
                    <span>{{ $order->created_at->format('d F Y, H:i') }} WIB</span>
                </div>
                <div class="info-item">
                    <label>Status:</label>
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>
                <div class="info-item">
                    <label>Metode Pembayaran:</label>
                    <span>{{ $order->payment->method_name ?? 'Belum dipilih' }}</span>
                </div>
                @if($order->payment && $order->payment->status)
                    <div class="info-item">
                        <label>Status Pembayaran:</label>
                        <span class="payment-status status-{{ $order->payment->status }}">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="info-card">
            <h2 class="card-title">
                <i class="fas fa-user"></i>
                Informasi Pelanggan
            </h2>
            <div class="customer-info">
                <div class="customer-item">
                    <i class="fas fa-user-circle"></i>
                    <div>
                        <label>Nama:</label>
                        <span>{{ $order->customer_name }}</span>
                    </div>
                </div>
                <div class="customer-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <label>Telepon:</label>
                        <span>{{ $order->customer_phone }}</span>
                    </div>
                </div>
                @if($order->customer_address)
                    <div class="customer-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <label>Alamat:</label>
                            <span>{{ $order->customer_address }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="info-card">
            <h2 class="card-title">
                <i class="fas fa-shopping-bag"></i>
                Detail Items
            </h2>
            <div class="items-table-wrapper">
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="item-info">
                                    <div class="item-name">{{ $item->menu_name }}</div>
                                    @if($item->notes)
                                        <div class="item-notes">
                                            <i class="fas fa-sticky-note"></i>
                                            {{ $item->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="item-qty">{{ $item->quantity }}</td>
                                <td class="item-subtotal">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="info-card summary-card">
            <h2 class="card-title">
                <i class="fas fa-calculator"></i>
                Ringkasan Pembayaran
            </h2>
            <div class="summary-content">
                <div class="summary-row">
                    <span>Subtotal Items:</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($order->total_discount > 0)
                    <div class="summary-row discount">
                        <span>Diskon:</span>
                        <span>-Rp {{ number_format($order->total_discount, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if($order->service_charge > 0)
                    <div class="summary-row">
                        <span>Biaya Layanan:</span>
                        <span>Rp {{ number_format($order->service_charge, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="summary-row total">
                    <span>Total Akhir:</span>
                    <span>Rp {{ number_format($order->final_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-section">
            <div class="action-buttons">
                @if($order->canBeCancelled())
                    <form method="POST" action="{{ route('order.cancel', $order->order_id) }}" class="cancel-form">
                        @csrf
                        <button type="submit" class="btn btn-cancel" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                            <i class="fas fa-times"></i>
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif

                @if($order->status === 'ready')
                    <form method="POST" action="{{ route('order.confirm', $order->order_id) }}" class="confirm-form">
                        @csrf
                        <button type="submit" class="btn btn-confirm">
                            <i class="fas fa-check"></i>
                            Konfirmasi Pesanan Diterima
                        </button>
                    </form>
                @endif

                @if($order->status === 'completed')
                    <form method="POST" action="{{ route('order.reorder', $order->order_id) }}" class="reorder-form">
                        @csrf
                        <button type="submit" class="btn btn-reorder">
                            <i class="fas fa-redo"></i>
                            Pesan Lagi
                        </button>
                    </form>
                @endif

                <a href="{{ route('order.receipt', $order->order_id) }}" class="btn btn-receipt">
                    <i class="fas fa-receipt"></i>
                    Download Struk
                </a>
            </div>
        </div>

        <!-- Order Timeline -->
        @if($order->status !== 'pending')
            <div class="info-card timeline-card">
                <h2 class="card-title">
                    <i class="fas fa-history"></i>
                    Status Timeline
                </h2>
                <div class="timeline">
                    <div class="timeline-item {{ $order->status === 'pending' || in_array($order->status, ['confirmed', 'preparing', 'ready', 'completed']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Pesanan Diterima</h4>
                            <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['confirmed', 'preparing', 'ready', 'completed']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Pesanan Dikonfirmasi</h4>
                            <p>Pesanan telah dikonfirmasi oleh restaurant</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['preparing', 'ready', 'completed']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Sedang Diproses</h4>
                            <p>Pesanan sedang dipersiapkan</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ in_array($order->status, ['ready', 'completed']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Siap Diambil</h4>
                            <p>Pesanan siap untuk diambil</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $order->status === 'completed' ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <h4>Pesanan Selesai</h4>
                            <p>Pesanan telah diterima pelanggan</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if(session('print_mode'))
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
@endif
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/order-detail.js') }}"></script>
@endpush