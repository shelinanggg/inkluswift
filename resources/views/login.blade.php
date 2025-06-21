<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Login</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
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
        <div class="login-container">
            <h1>Login</h1>
            
            {{-- Display validation errors --}}
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Display success message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="Email" 
                       value="{{ old('email') }}" 
                       required>
                
                <div class="password-container">
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Password" 
                           required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">Lihat</button>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="signup-link">
                <a href="{{route('register')}}">Belum punya akun?</a>
            </div>
        </div>
    </div>

    <script src="{{asset('js/login.js')}}"></script>
</body>
</html>