<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift History Pemesanan</title>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman -->
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/order-history.css">
    <!-- Script aksesibilitas -->
    <script src="js/accessibility.js"></script>
</head>
<body>
    <!-- Header with logo and auth buttons -->
    <header>
        <div class="logo">
            <img src="Assets/logo hd.png" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
        </div>
        <div class="auth-buttons">
            <a href="cart.html" class="btn btn-primary">
                <img src="Assets/cart.png" alt="Cart">
                Keranjang</a>
            <a href="profile.html" class="btn btn-primary">
                <img src="Assets/profile.png" alt="Profile">
                Profil</a>
        </div>
    </header>

    <!-- Alert Messages -->
    <div id="alertContainer" class="alert-container">
        <!-- Dynamic alerts will be inserted here -->
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="profile-header">
                <div class="profile-pic">
                    <span id="profileInitial">U</span>
                </div>
                <span id="userName">User Name</span>
            </div>
            <div class="menu-items">
                <a href="profile.html" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 20C18 16.6863 15.3137 14 12 14C8.68629 14 6 16.6863 6 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Profil Saya
                </a>
                <a href="change-password.html" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9S13.1 11 12 11 10 10.1 10 9 10.9 7 12 7ZM18 11C18 15.1 15.64 18.78 12 19.5C8.36 18.78 6 15.1 6 11V6.3L12 3.18L18 6.3V11Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Ganti Password
                </a>
                <a href="order-history.html" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    History Pemesanan
                </a>
            </div>
            <button type="button" class="logout-btn" onclick="logout()">
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
                
                <!-- Filter Status -->
                <div class="filter-section">
                    <label for="statusFilter">Filter Status:</label>
                    <select id="statusFilter" onchange="filterOrders()">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Diproses</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                </div>
            </div>

            <!-- Orders List -->
            <div class="orders-container" id="ordersContainer">
                <!-- Loading state -->
                <div class="loading" id="loadingState">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat histori pesanan...</p>
                </div>

                <!-- Empty state -->
                <div class="empty-state" id="emptyState" style="display: none;">
                    <i class="fas fa-receipt"></i>
                    <h3>Belum ada pesanan</h3>
                    <p>Anda belum memiliki riwayat pesanan</p>
                    <a href="menu.html" class="btn btn-primary">Mulai Pesan</a>
                </div>

                <!-- Orders will be loaded here -->
                <div id="ordersList"></div>
            </div>

            <!-- Pagination -->
            <div class="pagination-container" id="paginationContainer" style="display: none;">
                <div class="pagination" id="pagination">
                    <!-- Pagination buttons will be generated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Order Detail Modal -->
    <div id="orderDetailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Pesanan</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body" id="orderDetailContent">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content small">
            <div class="modal-header">
                <h3 id="confirmTitle">Konfirmasi</h3>
                <span class="close" onclick="closeConfirmModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Apakah Anda yakin?</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeConfirmModal()">Batal</button>
                    <button class="btn btn-danger" id="confirmButton" onclick="confirmAction()">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script src="js/order-history.js"></script>
</body>
</html>