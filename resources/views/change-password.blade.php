<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift - Ganti Password</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman Anda -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
    <style>
        /* Password specific styles */
        .password-field {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 16px;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #333;
        }
        
        .password-strength {
            margin-top: 8px;
            display: none;
        }
        
        .password-strength.show {
            display: block;
        }
        
        .strength-bar-container {
            width: 100%;
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
        }
        
        .strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease, background-color 0.3s ease;
            border-radius: 2px;
        }
        
        .password-strength-text {
            font-size: 12px;
            font-weight: 500;
        }
        
        .password-strength-text.weak {
            color: #ff4757;
        }
        
        .password-strength-text.medium {
            color: #ffa502;
        }
        
        .password-strength-text.strong {
            color: #3742fa;
        }
        
        .password-strength-text.very-strong {
            color: #2ed573;
        }
        
        .password-confirm-feedback {
            margin-top: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .password-confirm-feedback.match {
            color: #2ed573;
        }
        
        .password-confirm-feedback.no-match {
            color: #ff4757;
        }
        
        .password-requirements {
            margin-top: 8px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #FF4B3A;
        }
        
        .password-requirements h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 16px;
        }
        
        .password-requirements li {
            font-size: 12px;
            color: #666;
            margin-bottom: 2px;
        }
        
        .form-input[type="password"] {
            padding-right: 45px;
        }
        
        .password-form-group {
            margin-bottom: 24px;
        }
        
        .btn-change-password {
            background: linear-gradient(135deg, #FF4B3A 0%, #FF4B3A 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-change-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-change-password:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .btn-clear {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 12px;
        }
        
        .btn-clear:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
    </style>
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
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                <a href="{{route('change-password')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1ZM12 7C13.1 7 14 7.9 14 9S13.1 11 12 11 10 10.1 10 9 10.9 7 12 7ZM18 11C18 15.1 15.64 18.78 12 19.5C8.36 18.78 6 15.1 6 11V6.3L12 3.18L18 6.3V11Z" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Ganti Password
                </a>
                <a href="{{route('order-history.index')}}" class="menu-item">
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
                <h2>Ganti Password</h2>
            </div>

            <!-- Password Change Form -->
            <form action="{{route('update-password')}}" method="POST" id="password-form" class="profile-form">
                @csrf
                
                <!-- Current Password -->
                <div class="password-form-group">
                    <label class="form-label" for="current_password">Password Lama</label>
                    <div class="password-field">
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               class="form-input" 
                               placeholder="Masukkan password lama Anda"
                               autocomplete="current-password">
                        <span class="password-toggle" onclick="togglePasswordVisibility('current_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="password-form-group">
                    <label class="form-label" for="new_password">Password Baru</label>
                    <div class="password-field">
                        <input type="password" 
                               name="new_password" 
                               id="new_password" 
                               class="form-input" 
                               placeholder="Masukkan password baru"
                               autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePasswordVisibility('new_password')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="password-strength" id="password-strength">
                        <div class="strength-bar-container">
                            <div class="strength-bar"></div>
                        </div>
                        <span class="password-strength-text"></span>
                    </div>
                    
                    @error('new_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="password-form-group">
                    <label class="form-label" for="new_password_confirmation">Konfirmasi Password Baru</label>
                    <div class="password-field">
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation" 
                               class="form-input" 
                               placeholder="Masukkan ulang password baru"
                               autocomplete="new-password">
                        <span class="password-toggle" onclick="togglePasswordVisibility('new_password_confirmation')">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    
                    <!-- Password Confirmation Feedback -->
                    <div class="password-confirm-feedback"></div>
                </div>

                <!-- Password Requirements -->
                <div class="password-requirements">
                    <h4>Syarat Password:</h4>
                    <ul>
                        <li>Minimal 6 karakter</li>
                        <li>Mengandung huruf besar dan kecil</li>
                        <li>Mengandung minimal 1 angka</li>
                        <li>Mengandung minimal 1 karakter khusus (!@#$%^&*)</li>
                        <li>Berbeda dari password lama</li>
                    </ul>
                </div>

                <!-- Form Actions -->
                <div class="form-actions" style="margin-top: 32px;">
                    <button type="submit" class="btn-change-password">
                        <i class="fas fa-key"></i>
                        Ubah Password
                    </button>
                    <button type="button" class="btn-clear" onclick="clearPasswordForm()">
                        <i class="fas fa-eraser"></i>
                        Bersihkan Form
                    </button>
                </div>
            </form>

            <!-- Security Tips -->
            <div class="security-tips" style="margin-top: 40px; padding: 20px; background: linear-gradient(135deg, #f5f7fa 0%, #FF4B3A 200%); border-radius: 12px;">
                <h3 style="margin: 0 0 16px 0; color: #333; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-shield-alt"></i>
                    Tips Keamanan
                </h3>
                <ul style="margin: 0; padding-left: 20px; color: #666;">
                    <li style="margin-bottom: 8px;">Gunakan password yang unik untuk setiap akun</li>
                    <li style="margin-bottom: 8px;">Jangan gunakan informasi pribadi dalam password</li>
                    <li style="margin-bottom: 8px;">Ganti password secara berkala</li>
                    <li style="margin-bottom: 8px;">Jangan bagikan password kepada orang lain</li>
                    <li>Gunakan password manager untuk menyimpan password dengan aman</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script src="{{asset('js/profile.js')}}"></script>
</body>
</html>