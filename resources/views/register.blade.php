<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Sign Up</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman Anda -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
</head>
<body>
    <div class="left-panel">
        <div class="logo-container">
            <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo" class="logo-image">
        </div>
        <div class="logo-text">InkluSwift</div>
    </div>

    <div class="right-panel">
        <div class="signup-container">
            <h1>Sign Up</h1>
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Display success message -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <input type="text" name="name" placeholder="Nama" value="{{ old('name') }}" required>
                
                <input type="tel" name="phone" placeholder="Nomor Telepon" value="{{ old('phone') }}">
                
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

                <!-- <input type="text" name="address" placeholder="Alamat" value="{{ old('address') }}"> -->

                <select name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>

                <!-- <input type="file" name="profile_picture" accept="image/*"> -->

                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">Lihat</button>
                </div>

                <div class="password-container">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">Lihat</button>
                </div>

                <button type="submit" class="signup-btn">Sign Up</button>
            </form>
            
            <div class="login-link">
                <a href="{{route('login')}}">Sudah punya akun?</a>
            </div>
        </div>
    </div>

    <script src="{{asset('js/register.js')}}"></script>
</body>
</html>