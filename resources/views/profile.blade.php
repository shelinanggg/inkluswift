<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift My Profile</title>
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
        }
        
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .form-group {
            background-color: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .form-value {
            font-size: 1rem;
            color: #333;
        }
        
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .edit-btn {
            background: none;
            border: none;
            color: #ff5045;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        
        .edit-btn svg {
            width: 18px;
            height: 18px;
            margin-right: 5px;
        }
        
        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
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
                <a href="prof.html" class="btn btn-primary">
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
                <a href="{{route('profile')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 20C18 16.6863 15.3137 14 12 14C8.68629 14 6 16.6863 6 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Profil Saya
                </a>
                <a href="{{route('history')}}" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    History Pemesanan
                </a>
            </div>
            <button class="logout-btn" onclick="window.location.href='{{route('landing')}}'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H9M16 17L21 12M21 12L16 7M21 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </button>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="content-header">
                <h2>Profil Saya</h2>
            </div>
            <div class="profile-form">
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Nama</label>
                        <button class="edit-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value">Mario</div>
                </div>
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Nomor Telepon</label>
                        <button class="edit-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value">+6234567890</div>
                </div>
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label">Email</label>
                        <button class="edit-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value">mario123@email.com</div>
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