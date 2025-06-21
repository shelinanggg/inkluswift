<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Management - InkluSwift</title>
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{ asset('Assets/logo hd.png') }}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
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
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Dashboard
        </button>
        
        <h1 class="page-title">Order Management</h1>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon pending">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['pending_orders'] }}</h3>
                    <p>Pending Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon confirmed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['confirmed_orders'] }}</h3>
                    <p>Confirmed Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon preparing">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['preparing_orders'] }}</h3>
                    <p>Preparing Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon revenue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p>Total Revenue</p>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="search-filter">
            <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm">
                <div class="filter-row">
                    <div class="search-box">
                        <input type="text" name="search" id="searchInput" placeholder="Search by Order ID" value="{{ request('search') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    
                    <select name="status" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    
                    <input type="date" name="date_from" id="dateFrom" value="{{ request('date_from') }}">
                    <input type="date" name="date_to" id="dateTo" value="{{ request('date_to') }}">
                    
                    <button type="submit" class="filter-btn">Filter</button>
                    <a href="{{ route('admin.orders.index') }}" class="reset-btn">Reset</a>
                </div>
            </form>
        </div>
        
        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <form method="POST" action="{{ route('admin.orders.bulk-update') }}" id="bulkForm">
                @csrf
                <div class="bulk-controls">
                    <span id="selectedCount">0 selected</span>
                    <select name="status" required>
                        <option value="">Change Status To...</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="preparing">Preparing</option>
                        <option value="ready">Ready</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <button type="submit" class="bulk-btn">Update Selected</button>
                    <button type="button" class="cancel-bulk-btn" id="cancelBulk">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Orders Table -->
        <div class="order-table">
            <div class="order-header">
                <div><input type="checkbox" id="selectAll"></div>
                <div>ORDER ID</div>
                <div>CUSTOMER</div>
                <div>PHONE</div>
                <div>STATUS</div>
                <div>TOTAL</div>
                <div>DATE</div>
                <div>ACTION</div>
            </div>
            
            @if($orders->count() > 0)
                @foreach($orders as $order)
                <div class="order-item" data-order-id="{{ $order->order_id }}">
                    <div class="checkbox-cell">
                        <input type="checkbox" class="order-checkbox" value="{{ $order->order_id }}">
                    </div>
                    <div class="order-id">{{ $order->order_id }}</div>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="customer-phone">{{ $order->customer_phone }}</div>
                    <div class="status">
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    </div>
                    <div class="total-amount">{{ $order->formatted_final_amount }}</div>
                    <div class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    <div class="actions">
                        <button class="action-btn view-btn" onclick="viewOrder('{{ $order->order_id }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                        </button>
                        <button class="action-btn edit-btn" onclick="showStatusModal('{{ $order->order_id }}', '{{ $order->status }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        @if($order->canBeCancelled())
                        <button class="action-btn cancel-btn" onclick="showCancelModal('{{ $order->order_id }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-data">No orders found. Orders will appear here when customers place them.</div>
            @endif
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            <div class="showing-info">
                Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
            </div>
            
            <div class="pagination-buttons">
                {{ $orders->links() }}
            </div>
        </div>
        
        <!-- Export Button -->
        <div class="export-section">
            <button class="export-btn" onclick="showExportModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Export Orders
            </button>
        </div>
    </div>
    
    <!-- Status Update Modal -->
    <div class="modal" id="statusModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Order Status</h3>
                <button class="close-btn" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <form method="POST" id="statusForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Order ID</label>
                        <input type="text" id="modalOrderId" readonly>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="modalStatus" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready">Ready</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Add notes about status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('statusModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Cancel Order Modal -->
    <div class="modal" id="cancelModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Cancel Order</h3>
                <button class="close-btn" onclick="closeModal('cancelModal')">&times;</button>
            </div>
            <form method="POST" id="cancelForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Order ID</label>
                        <input type="text" id="cancelOrderId" readonly>
                    </div>
                    <div class="form-group">
                        <label>Cancellation Reason</label>
                        <textarea name="reason" rows="3" required placeholder="Please provide reason for cancellation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('cancelModal')">Cancel</button>
                    <button type="submit" class="btn-danger">Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Export Modal -->
    <div class="modal" id="exportModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Export Orders</h3>
                <button class="close-btn" onclick="closeModal('exportModal')">&times;</button>
            </div>
            <form method="GET" action="{{ route('admin.orders.export') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" required>
                    </div>
                    <div class="form-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" required>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready">Ready</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal('exportModal')">Cancel</button>
                    <button type="submit" class="btn-primary">Export CSV</button>
                </div>
            </form>
        </div>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>
    
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error" id="errorAlert">
            {{ session('error') }}
        </div>
    @endif

    <script src="{{ asset('js/orders.js') }}"></script>
</body>
</html>