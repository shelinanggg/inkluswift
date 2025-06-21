<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail - {{ $order->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .order-detail {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .order-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .order-id {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .order-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        
        .status-pending { background: #fff3cd; color: #856404; }
        .status-confirmed { background: #d1ecf1; color: #0c5460; }
        .status-preparing { background: #cce5ff; color: #004085; }
        .status-ready { background: #d4edda; color: #155724; }
        .status-completed { background: #d4edda; color: #155724; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
        
        .order-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .info-section h4 {
            color: #34495e;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
        
        .info-item {
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: bold;
            color: #7f8c8d;
            display: inline-block;
            width: 120px;
        }
        
        .items-section {
            margin-top: 2rem;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .items-table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .items-table tr:hover {
            background: #f8f9fa;
        }
        
        .order-summary {
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }
        
        .summary-row.total {
            font-weight: bold;
            font-size: 1.1rem;
            color: #2c3e50;
            border-top: 2px solid #ddd;
            padding-top: 0.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="order-detail">
        <div class="order-header">
            <div class="order-id">Order #{{ $order->order_id }}</div>
            <span class="order-status status-{{ $order->status }}">{{ $order->status_label }}</span>
        </div>
        
        <div class="order-info">
            <div class="info-section">
                <h4>Customer Information</h4>
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    {{ $order->customer_name ?? 'N/A' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    {{ $order->customer_phone ?? 'N/A' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Address:</span>
                    {{ $order->customer_address ?? 'N/A' }}
                </div>
                @if($order->user)
                <div class="info-item">
                    <span class="info-label">User ID:</span>
                    {{ $order->user->user_id }}
                </div>
                @endif
            </div>
            
            <div class="info-section">
                <h4>Order Information</h4>
                <div class="info-item">
                    <span class="info-label">Order Date:</span>
                    {{ $order->created_at->format('d M Y, H:i') }}
                </div>
                @if($order->confirmed_at)
                <div class="info-item">
                    <span class="info-label">Confirmed:</span>
                    {{ $order->confirmed_at->format('d M Y, H:i') }}
                </div>
                @endif
                @if($order->completed_at)
                <div class="info-item">
                    <span class="info-label">Completed:</span>
                    {{ $order->completed_at->format('d M Y, H:i') }}
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Payment:</span>
                    {{ $order->payment->method_name ?? 'N/A' }}
                </div>
                @if($order->notes)
                <div class="info-item">
                    <span class="info-label">Notes:</span>
                    {{ $order->notes }}
                </div>
                @endif
            </div>
        </div>
        
        <div class="items-section">
            <h4>Order Items</h4>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->menu_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->formatted_price }}</td>
                        <td>
                            @if($item->discount_percent > 0)
                                {{ $item->discount_percent }}%
                                ({{ $item->formatted_discount_amount }})
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $item->formatted_subtotal_after_discount }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>{{ $order->formatted_total_amount }}</span>
            </div>
            @if($order->total_discount > 0)
            <div class="summary-row">
                <span>Total Discount:</span>
                <span>-{{ $order->formatted_total_discount }}</span>
            </div>
            @endif
            <div class="summary-row">
                <span>Service Charge:</span>
                <span>{{ $order->formatted_service_charge }}</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span>{{ $order->formatted_final_amount }}</span>
            </div>
        </div>
        
        @if($order->proof_image)
        <div class="proof-section" style="margin-top: 2rem;">
            <h4>Payment Proof</h4>
            <img src="{{ asset('storage/' . $order->proof_image) }}" alt="Payment Proof" style="max-width: 300px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        </div>
        @endif
    </div>
</body>
</html>