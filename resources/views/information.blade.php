<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Info</title>
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

        .settings-card {
            background-color: white;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }
        
        .settings-content {
            flex: 1;
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .content-title {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        
        .infos-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .info-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #eee;
            text-decoration: none;
            color: #333;
            transition: all 0.2s;
            background-color: white;
        }
        
        .info-text {
            font-size: 16px;
            font-weight: 500;
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
                <img src="Assets/logo hd.png" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </div>
            <div class="auth-buttons">
                <a href="{{route('cart')}}" class="btn btn-primary">
                    <img src="Assets/cart.png" alt="Cart">
                    Keranjang</a>
                <a href="{{route('profile')}}" class="btn btn-primary">
                    <img src="Assets/profile.png" alt="Profile">
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
                <a href="{{route('profile')}}" class="menu-item">
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
                <a href="{{route('setting')}}" class="menu-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M19.4 15C19.1277 15.6171 19.2583 16.3378 19.73 16.83L19.79 16.89C20.1656 17.2656 20.3765 17.7848 20.3765 18.325C20.3765 18.8652 20.1656 19.3844 19.79 19.76C19.4144 20.1356 18.8952 20.3465 18.355 20.3465C17.8148 20.3465 17.2956 20.1356 16.92 19.76L16.86 19.7C16.3678 19.2283 15.6471 19.0977 15.03 19.37C14.4289 19.6312 14.0326 20.2051 14 20.85V21C14 21.5304 13.7893 22.0391 13.4142 22.4142C13.0391 22.7893 12.5304 23 12 23C11.4696 23 10.9609 22.7893 10.5858 22.4142C10.2107 22.0391 10 21.5304 10 21V20.91C9.95693 20.2449 9.53834 19.6573 8.92 19.4C8.30293 19.1277 7.58224 19.2583 7.09 19.73L7.03 19.79C6.65442 20.1656 6.13517 20.3765 5.595 20.3765C5.05483 20.3765 4.53558 20.1656 4.16 19.79C3.78442 19.4144 3.57351 18.8952 3.57351 18.355C3.57351 17.8148 3.78442 17.2956 4.16 16.92L4.22 16.86C4.69171 16.3678 4.82231 15.6471 4.55 15.03C4.28869 14.4289 3.71476 14.0326 3.07 14H3C2.46957 14 1.96086 13.7893 1.58579 13.4142C1.21071 13.0391 1 12.5304 1 12C1 11.4696 1.21071 10.9609 1.58579 10.5858C1.96086 10.2107 2.46957 10 3 10H3.09C3.75513 9.95693 4.34274 9.53834 4.6 8.92C4.87231 8.30293 4.74171 7.58224 4.27 7.09L4.21 7.03C3.83442 6.65442 3.62351 6.13517 3.62351 5.595C3.62351 5.05483 3.83442 4.53558 4.21 4.16C4.58558 3.78442 5.10483 3.57351 5.645 3.57351C6.18517 3.57351 6.70442 3.78442 7.08 4.16L7.14 4.22C7.63224 4.69171 8.35293 4.82231 8.97 4.55H9C9.60114 4.28869 9.99736 3.71476 10.03 3.07V3C10.03 2.46957 10.2407 1.96086 10.6158 1.58579C10.9909 1.21071 11.4996 1 12.03 1C12.5604 1 13.0691 1.21071 13.4442 1.58579C13.8193 1.96086 14.03 2.46957 14.03 3V3.09C14.0726 3.75513 14.4912 4.34274 15.11 4.6C15.7271 4.87231 16.4478 4.74171 16.94 4.27L17 4.21C17.3756 3.83442 17.8948 3.62351 18.435 3.62351C18.9752 3.62351 19.4944 3.83442 19.87 4.21C20.2456 4.58558 20.4565 5.10483 20.4565 5.645C20.4565 6.18517 20.2456 6.70442 19.87 7.08L19.81 7.14C19.3383 7.63224 19.2077 8.35293 19.48 8.97V9C19.7413 9.60114 20.3152 9.99736 20.96 10.03H21C21.5304 10.03 22.0391 10.2407 22.4142 10.6158C22.7893 10.9909 23 11.4996 23 12.03C23 12.5604 22.7893 13.0691 22.4142 13.4442C22.0391 13.8193 21.5304 14.03 21 14.03H20.91C20.2449 14.0726 19.6573 14.4912 19.4 15.11V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Pengaturan Akun
                </a>
                <a href="{{route('information')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Informasi & Panduan Layanan
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
                <h2>Informasi & Panduan Layanan</h2>
            </div>
            <div class="infos-options">
                <a href="#" class="info-option">
                  <div class="info-text">Tentang Kami</div>
                  <div class="info-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9 18L15 12L9 6" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </a>
                
                <a href="#" class="info-option">
                  <div class="info-text">Kebijakan & Keamanan</div>
                  <div class="info-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M9 18L15 12L9 6" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                </a>

                <a href="#" class="info-option">
                    <div class="info-text">Pusat Bantuan</div>
                    <div class="info-icon">
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="#999" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                  </a>
              </div>
            </div>
        </div>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>
</body>
</html>