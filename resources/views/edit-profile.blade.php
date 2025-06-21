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
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
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
                <a href="{{route('edit-profile')}}" class="menu-item active">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18 20C18 16.6863 15.3137 14 12 14C8.68629 14 6 16.6863 6 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Profil Saya
                </a>
                <a href="{{route('change-password')}}" class="menu-item">
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
                <h2>Profil Saya</h2>
            </div>

            <!-- Profile Form -->
            <form action="{{route('update-profile')}}" method="POST" enctype="multipart/form-data" class="profile-form">
                @csrf
                
                <!-- Profile Picture Section -->
                <div class="form-group profile-picture-section">
                    <div class="form-header">
                        <label class="form-label">Foto Profil</label>
                    </div>
                    <div class="profile-picture-container">
                        <div class="current-picture">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" id="currentProfilePic">
                            @else
                                <div class="default-avatar" id="currentProfilePic">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="picture-actions">
                            <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" style="display: none;">
                            <button type="button" class="btn-upload" onclick="document.getElementById('profilePictureInput').click()">
                                <i class="fas fa-camera"></i> Pilih Foto
                            </button>
                            @if($user->profile_picture)
                                <button type="button" class="btn-remove" onclick="removeProfilePicture()">
                                    <i class="fas fa-trash"></i> Hapus Foto
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Name Field -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label" for="name">Nama</label>
                        <button type="button" class="edit-btn" onclick="toggleEdit('name')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value" id="name-display">{{ $user->name }}</div>
                    <input type="text" name="name" id="name-input" class="form-input hidden" value="{{ old('name', $user->name) }}">
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label" for="email">Email</label>
                        <button type="button" class="edit-btn" onclick="toggleEdit('email')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value" id="email-display">{{ $user->email }}</div>
                    <input type="email" name="email" id="email-input" class="form-input hidden" value="{{ old('email', $user->email) }}">
                </div>

                <!-- Phone Field -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label" for="phone">Nomor Telepon</label>
                        <button type="button" class="edit-btn" onclick="toggleEdit('phone')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value" id="phone-display">{{ $user->phone ?? 'Belum diisi' }}</div>
                    <input type="text" name="phone" id="phone-input" class="form-input hidden" value="{{ old('phone', $user->phone) }}">
                </div>

                <!-- Address Field -->
                <div class="form-group">
                    <div class="form-header">
                        <label class="form-label" for="address">Alamat</label>
                        <button type="button" class="edit-btn" onclick="toggleEdit('address')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13M18.5 2.5C18.8978 2.10217 19.4374 1.87868 20 1.87868C20.5626 1.87868 21.1022 2.10217 21.5 2.5C21.8978 2.89782 22.1213 3.43739 22.1213 4C22.1213 4.56261 21.8978 5.10217 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="form-value" id="address-display">{{ $user->address ?? 'Belum diisi' }}</div>
                    <textarea name="address" id="address-input" class="form-input hidden" rows="3">{{ old('address', $user->address) }}</textarea>
                </div>

                <!-- Save Button -->
                <div class="form-actions">
                    <button type="submit" class="btn-save" id="saveButton" style="display: none;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn-cancel" id="cancelButton" style="display: none;" onclick="cancelEdit()">
                        <i class="fas fa-times"></i> Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Remove Profile Picture Form (Hidden) -->
    <form id="removeProfilePictureForm" action="{{route('remove-profile-picture')}}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Footer -->
    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script src="{{asset('js/profile.js')}}"></script>
</body>
</html>