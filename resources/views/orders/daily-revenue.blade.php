<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daily Revenue - InkluSwift</title>
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
        <button class="back-btn" onclick="window.location.href='{{ route('orders.monitor') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back to Monitor
        </button>
        
        <h1 class="page-title">Daily Revenue Report</h1>
        
        <!-- Date Filter -->
        <div class="date-filter">
            <form method="GET" action="{{ route('orders.daily-revenue') }}">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
            </form>
        </div>
        
        <!-- Revenue Stats -->
        <div class="stats-cards">
            <div class="stat-card revenue">
                <div class="stat-icon">💰</div>
                <div class="stat-info">
                    <h3>Total Revenue</h3>
                    <p class="stat-value">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div class="stat-card orders">
                <div class="stat-icon">📦</div>
                <div class="stat-info">
                    <h3>Total Orders</h3>
                    <p class="stat-value">{{ $totalOrders }}</p>
                </div>
            </div>
            
            <div class="stat-card completed">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <h3>Average Order</h3>
                    <p class="stat-value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-table">
            <div class="table-header">
                <div>ORDER ID</div>
                <div>CUSTOMER</div>
                <div>ITEMS</div>
                <div>AMOUNT</div>
                <div>PAYMENT</div>
                <div>TIME</div>
            </div>
            
            <div id="ordersContainer">
                @forelse($dailyOrders as $order)
                <div class="order-row">
                    <div class="order-id">{{ $order->order_id }}</div>
                    <div class="customer-name">{{ $order->customer_name ?? 'N/A' }}</div>
                    <div class="items-count">{{ $order->orderItems->count() }} items</div>
                    <div class="amount">{{ $order->formatted_final_amount }}</div>
                    <div class="payment">{{ $order->payment->method_name ?? 'N/A' }}</div>
                    <div class="time">{{ $order->completed_at->format('H:i') }}</div>
                </div>
                @empty
                <div class="no-data">No completed orders found for {{ $date }}.</div>
                @endforelse
            </div>
        </div>
        
        <!-- Order Items Breakdown -->
        @if($dailyOrders->count() > 0)
        <div class="order-breakdown">
            <h3>Order Items Breakdown</h3>
            <div class="breakdown-table">
                <div class="breakdown-header">
                    <div>ITEM NAME</div>
                    <div>QUANTITY SOLD</div>
                    <div>REVENUE</div>
                </div>
                
                @php
                    $itemBreakdown = [];
                    foreach($dailyOrders as $order) {
                        foreach($order->orderItems as $item) {
                            $itemName = $item->menu_name;
                            if(!isset($itemBreakdown[$itemName])) {
                                $itemBreakdown[$itemName] = [
                                    'quantity' => 0,
                                    'revenue' => 0
                                ];
                            }
                            $itemBreakdown[$itemName]['quantity'] += $item->quantity;
                            $itemBreakdown[$itemName]['revenue'] += $item->subtotal_after_discount;
                        }
                    }
                    // Sort by revenue desc
                    uasort($itemBreakdown, function($a, $b) {
                        return $b['revenue'] <=> $a['revenue'];
                    });
                @endphp
                
                @foreach($itemBreakdown as $itemName => $data)
                <div class="breakdown-row">
                    <div class="item-name">{{ $itemName }}</div>
                    <div class="quantity">{{ $data['quantity'] }}</div>
                    <div class="revenue">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>

    <style>
        .date-filter {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .date-filter label {
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .date-filter input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .order-breakdown {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .order-breakdown h3 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        
        .breakdown-table {
            border-radius: 5px;
            overflow: hidden;
        }
        
        .breakdown-header {
            background: #34495e;
            color: white;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            padding: 1rem;
            font-weight: bold;
        }
        
        .breakdown-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            padding: 1rem;
            border-bottom: 1px solid #ecf0f1;
            align-items: center;
        }
        
        .breakdown-row:hover {
            background: #f8f9fa;
        }
        
        .breakdown-row:last-child {
            border-bottom: none;
        }
        
        .items-count {
            font-size: 0.9rem;
            color: #7f8c8d;
        }
    </style>
</body>
</html>