<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Orders</title>
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
            background-color: #f5f5f5;
        }
        
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
            align-items: center;
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
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: white;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .page-title {
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .search-filter {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 5px;
            padding: 8px 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .search-box input {
            border: none;
            outline: none;
            width: 200px;
        }
        
        .filter-dropdown {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 5px;
            padding: 8px 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
        }
        
        .filter-dropdown span {
            margin-right: 10px;
        }
        
        .orders-table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .orders-header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 0.5fr;
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #777;
            text-transform: uppercase;
        }
        
        .order-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr 0.5fr;
            padding: 15px;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        
        .order-details {
            padding: 0 15px 15px;
            background-color: #fafafa;
            display: none;
        }
        
        .order-details.active {
            display: block;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .details-table th, .details-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .details-totals {
            display: flex;
            justify-content: flex-end;
            padding: 15px 0;
        }
        
        .totals-table {
            width: 300px;
        }
        
        .totals-table td {
            padding: 5px;
        }
        
        .totals-table td:last-child {
            text-align: right;
        }
        
        /* Style untuk status dropdown */
        .status-dropdown {
            border: none;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6' fill='none'%3E%3Cpath d='M1 1L5 5L9 1' stroke='currentColor' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 25px;
            text-transform: capitalize;
            width: 100px;
        }
        
        /* Warna untuk setiap status */
        .status-dropdown.waiting {
            background-color: #ffe9b1;
            color: #b86e00;
        }
        
        .status-dropdown.proceed {
            background-color: #b7ffb1;
            color: #007241;
        }
        
        .status-dropdown.cancelled {
            background-color: #ffb1b1;
            color: #a70000;
        }
        
        .status-dropdown.delivered {
            background-color: #e9b1ff;
            color: #5a0072;
        }
        
        .status-dropdown.done {
            background-color: #d1d1ff;
            color: #000072;
        }
        
        .details-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #f5f5f5;
            color: #777;
            border: none;
            cursor: pointer;
        }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .showing-info {
            display: flex;
            align-items: center;
            color: #777;
            font-size: 14px;
        }
        
        .showing-info select {
            margin: 0 5px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        
        .pagination-buttons {
            display: flex;
            align-items: center;
        }
        
        .pagination-buttons button {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            background-color: white;
            margin: 0 2px;
            cursor: pointer;
        }
        
        .pagination-buttons button.active {
            background-color: #FF5733;
            color: white;
            border-color: #FF5733;
        }
        
        footer {
            background-color: #222;
            color: #888;
            text-align: center;
            padding: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="Assets/logo hd.png" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>

        <div class="auth-buttons">
            <a href="{{route('landing')}}" class="btn btn-primary">
                <img src="Assets/logout.png" alt="Logout Icon">
                Logout
            </a>
        </div>
    </header>
    
    <div class="container">
        <button class="back-btn" onclick="window.location.href='{{route('admin')}}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </button>
        
        <h1 class="page-title">Pesanan Aktif</h1>
        
        <div class="search-filter">
            <div class="search-box">
                <input type="text" placeholder="Cari berdasar ID order">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </div>
            
            <div class="filter-dropdown">
                <span>Filter berdasar tanggal</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </div>
        </div>
        
        <div class="orders-table">
            <div class="orders-header">
                <div>Order ID</div>
                <div>Customer ID</div>
                <div>Created</div>
                <div>Location</div>
                <div>Total</div>
                <div>Payment</div>
                <div>Status</div>
                <div>Details</div>
            </div>
            
            <div>
                <div class="order-row">
                    <div>#06548</div>
                    <div>#C6548</div>
                    <div>2025/04/15 09:57:00</div>
                    <div>Jalan Mulyorejo</div>
                    <div>Rp65.400</div>
                    <div>Cards</div>
                    <div>
                        <select class="status-dropdown waiting" onchange="changeStatus(this)">
                            <option value="waiting" selected>Waiting</option>
                            <option value="proceed">Proceed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="delivered">Delivered</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                    <div>
                        <button class="details-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </button>
                    </div>
                </div>
                
                <div class="order-details active">
                    <table class="details-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Menu ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>QTY</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>#M6548</td>
                                <td>Cheese Burger</td>
                                <td>Rp35.000</td>
                                <td>x1</td>
                                <td>Rp35.000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>#M6548</td>
                                <td>Nasi Ayam Geprek</td>
                                <td>Rp20.000</td>
                                <td>x1</td>
                                <td>Rp20.000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>#M6548</td>
                                <td>Es Teh</td>
                                <td>Rp7.000</td>
                                <td>x1</td>
                                <td>Rp7.000</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div class="details-totals">
                        <table class="totals-table">
                            <tr>
                                <td>Subtotal</td>
                                <td>Rp62.000</td>
                            </tr>
                            <tr>
                                <td>Shipping</td>
                                <td>Rp3.400</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="order-row">
                <div>#06547</div>
                <div>#C6548</div>
                <div>2025/04/15 09:45:00</div>
                <div>Jalan Mulyorejo</div>
                <div>Rp65.400</div>
                <div>Cards</div>
                <div>
                    <select class="status-dropdown proceed" onchange="changeStatus(this)">
                        <option value="waiting">Waiting</option>
                        <option value="proceed" selected>Proceed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="delivered">Delivered</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <button class="details-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="order-row">
                <div>#06546</div>
                <div>#C6548</div>
                <div>2025/04/15 09:44:00</div>
                <div>Jalan Mulyorejo</div>
                <div>Rp65.400</div>
                <div>Cards</div>
                <div>
                    <select class="status-dropdown cancelled" onchange="changeStatus(this)">
                        <option value="waiting">Waiting</option>
                        <option value="proceed">Proceed</option>
                        <option value="cancelled" selected>Cancelled</option>
                        <option value="delivered">Delivered</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <button class="details-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="order-row">
                <div>#06545</div>
                <div>#C6548</div>
                <div>2025/04/15 09:41:00</div>
                <div>Jalan Mulyorejo</div>
                <div>Rp65.400</div>
                <div>Paypal</div>
                <div>
                    <select class="status-dropdown delivered" onchange="changeStatus(this)">
                        <option value="waiting">Waiting</option>
                        <option value="proceed">Proceed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="delivered" selected>Delivered</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <button class="details-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="order-row">
                <div>#06544</div>
                <div>#C6548</div>
                <div>2025/04/15 09:40:00</div>
                <div>Jalan Mulyorejo</div>
                <div>Rp65.400</div>
                <div>Paypal</div>
                <div>
                    <select class="status-dropdown done" onchange="changeStatus(this)">
                        <option value="waiting">Waiting</option>
                        <option value="proceed">Proceed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="delivered">Delivered</option>
                        <option value="done" selected>Done</option>
                    </select>
                </div>
                <div>
                    <button class="details-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="pagination">
            <div class="showing-info">
                Showing
                <select>
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                of 50
            </div>
            
            <div class="pagination-buttons">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                </button>
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>4</button>
                <button>5</button>
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                </button>
            </div>
        </div>
    </div>
    
    <footer>
        All rights Reserved © 2025, InkluSwift
    </footer>
    
    <script>
        // Toggle order details
        const detailButtons = document.querySelectorAll('.details-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', () => {
                const orderRow = button.closest('.order-row');
                const orderDetails = orderRow.nextElementSibling;
                if (orderDetails && orderDetails.classList.contains('order-details')) {
                    orderDetails.classList.toggle('active');
                }
            });
        });
        
        // Fungsi untuk mengubah status
        function changeStatus(select) {
            // Hapus semua class status sebelumnya
            select.classList.remove('waiting', 'proceed', 'cancelled', 'delivered', 'done');
            // Tambahkan class baru berdasarkan nilai yang dipilih
            select.classList.add(select.value);
            
            // Di sini Anda bisa menambahkan kode untuk menyimpan perubahan status ke server
            const orderId = select.closest('.order-row').querySelector('div:first-child').textContent;
            console.log('Status changed to: ' + select.value + ' for order: ' + orderId);
            
            // Contoh kode untuk mengirim ke server (uncomment jika diperlukan)
            /*
            fetch('/api/orders/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    orderId: orderId.replace('#', ''),
                    status: select.value
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                // Tampilkan notifikasi sukses jika perlu
            })
            .catch((error) => {
                console.error('Error:', error);
                // Tampilkan notifikasi error jika perlu
            });
            */
        }
    </script>
</body>
</html>