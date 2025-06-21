<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Monitor - InkluSwift</title>
    <link rel="stylesheet" href="{{ asset('css/order-monitor.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ route('admin') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
        </div>

        <div class="auth-buttons">
            <a href="{{ route('landing') }}" class="btn btn-primary">
                <img src="{{ asset('Assets/logout.png') }}" alt="Logout Icon">
                Logout
            </a>
        </div>
    </header>
    
    <div class="container">
        <button class="back-btn" onclick="window.location.href='{{ route('admin') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Dashboard
        </button>
        
        <h1 class="page-title">Order Monitor</h1>
        
        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card revenue">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Today's Revenue</h3>
                    <p class="stat-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="stat-card orders">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3>Today's Orders</h3>
                    <p class="stat-value">{{ $todayOrders->count() }}</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3>Pending Orders</h3>
                    <p class="stat-value">{{ $orderStats['pending'] ?? 0 }}</p>
                </div>
            </div>
            
            <div class="stat-card completed">
                <div class="stat-icon">✅</div>
                <div class="stat-info">
                    <h3>Completed Orders</h3>
                    <p class="stat-value">{{ $orderStats['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <a href="{{ route('orders.daily-revenue') }}" class="action-btn">📊 Daily Revenue</a>
            <button onclick="refreshOrders()" class="action-btn">🔄 Refresh</button>
            <button onclick="loadWeeklyStats()" class="action-btn">📈 Weekly Stats</button>
        </div>
        
        <!-- Orders Table -->
        <div class="orders-table">
            <div class="table-header">
                <div>ORDER ID</div>
                <div>CUSTOMER</div>
                <div>PHONE</div>
                <div>AMOUNT</div>
                <div>STATUS</div>
                <div>TIME</div>
                <div>ACTION</div>
            </div>
            
            <div id="ordersContainer">
                @forelse($todayOrders as $order)
                <div class="order-row" data-order-id="{{ $order->order_id }}">
                    <div class="order-id">{{ $order->order_id }}</div>
                    <div class="customer-name">{{ $order->customer_name ?? 'N/A' }}</div>
                    <div class="customer-phone">{{ $order->customer_phone ?? 'N/A' }}</div>
                    <div class="amount">{{ $order->formatted_final_amount }}</div>
                    <div class="status">
                        <span class="status-badge status-{{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <div class="time">{{ $order->created_at->format('H:i') }}</div>
                    <div class="actions">
                        <button onclick="viewOrder('{{ $order->order_id }}')" class="btn-view">View</button>
                        @if($order->canBeCancelled())
                        <select onchange="updateOrderStatus('{{ $order->order_id }}', this.value)" class="status-select">
                            <option value="">Change Status</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirm</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Complete</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                        </select>
                        @endif
                    </div>
                </div>
                @empty
                <div class="no-data">No orders found for today.</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Order Detail Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Order Details</h2>
            <div id="orderDetails">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>

    <script src="{{ asset('js/order-monitor.js') }}"></script>
</body>
</html>