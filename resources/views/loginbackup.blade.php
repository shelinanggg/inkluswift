<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Login</title>
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
            font-family: 'Arial', sans-serif;
        }
        
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        .left-panel {
            background-color: #FF5040;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        .logo-container {
            width: 400px;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: -3rem;
        }
        
        .logo-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .logo-text {
            color: white;
            font-size: 3rem;
            font-weight: bold;
        }
        
        .right-panel {
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background-color: white;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            font-size: 3rem;
            margin-bottom: 2rem;
            color: #333;
            text-align: center;
        }
        
        input {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border: none;
            background-color: #F1EBEB;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .password-container {
            position: relative;
            margin-bottom: 1rem;
        }
        
        .password-container input {
            margin-bottom: 0;
            padding-right: 40px;
        }
        
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            color: #777;
            font-size: 0.9rem;
        }
        
        .login-btn {
            width: 100%;
            padding: 1rem;
            background-color: #FF5040;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
            display: block;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
        }
        
        .signup-link {
            margin-top: 1rem;
            text-align: center;
        }
        
        .signup-link a {
            color: #FF5040;
            text-decoration: none;
        }
    </style>
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
            <form onsubmit="return handleLogin(event)">
                <input type="email" id="email" placeholder="Email" required>
                
                <div class="password-container">
                    <input type="password" id="password" placeholder="Password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password')">Lihat</button>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
            </form>
            <div class="signup-link">
                <a href="{{route('register')}}">Belum punya akun?</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleButton = passwordInput.nextElementSibling;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.textContent = 'Sembunyikan';
            } else {
                passwordInput.type = 'password';
                toggleButton.textContent = 'Lihat';
            }
        }

        function handleLogin(event) {
            event.preventDefault(); // Mencegah reload form

            const email = document.getElementById("email").value.trim();

            if (email === "admin@inkluswift.com") {
                window.location.href = "{{route('admin')}}";
            } else {
                window.location.href = "{{route('home')}}";
            }

            return false; // Supaya tidak submit default
        }
    </script>
</body>
</html>