<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Admin Dashboard</title>
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

        .hero {
            background-color: #ff5544;
            color: white;
            padding: 0px 0%;
            padding-left: 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .hero-text {
            flex: 1;
        }

        .hero-text h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero-image {
            flex: 1;
            min-width: 280px;
            text-align: right;
        }

        .hero-image img {
            width: 100%;
            max-width: 300px;
        }

        .categories {
            padding: 30px 10%;
            text-align: center;
        }

        .categories h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .category-list {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .category {
            text-align: center;
            text-decoration: none;
            color: #333;
            max-width: 150px;
        }

        .category-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 10px;
        }

        .category-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category h3 {
            font-size: 1rem;
        }

        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <a href="{{ route('admin') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
        </div>

        <div class="auth-buttons">
            <a href="{{route('landing')}}" class="btn btn-primary">
                <img src="Assets/logout.png" alt="Logout Icon">
                Logout
            </a>
        </div>
    </header>

    <section class="hero">
        <div class="hero-text">
            <h2>Pesanan masuk, pelanggan menanti!</h2>
            <p>Yuk, berikan pelayanan terbaik hari ini!</p>
        </div>
        <div class="hero-image">
            <img src="Assets/tangan.png" alt="Pelayanan Terbaik">
        </div>
    </section>

    <section class="categories">
        <h2>Fitur</h2>
        <div class="category-list">
            <a href="{{route('orders.monitor')}}" class="category">
                <div class="category-img">
                    <img src="Assets/pesanan.png" alt="Pesanan Aktif">
                </div>
                <h3>Pesanan Aktif</h3>
            </a>
            <a href="{{route('menu')}}" class="category">
                <div class="category-img">
                    <img src="Assets/menuuuu.png" alt="Menu">
                </div>
                <h3>Menu</h3>
            </a>
            <a href="{{route('account')}}" class="category">
                <div class="category-img">
                    <img src="Assets/akun.jpg" alt="Akun">
                </div>
                <h3>Akun</h3>
            </a>
            <a href="{{route('payment')}}" class="category">
                <div class="category-img">
                    <img src="Assets/pendapatan.png" alt="Pembayaran">
                </div>
                <h3>Metode Pembayaran</h3>
            </a>
        </div>
    </section>

    <footer>
        <p>All rights Reserved © 2025, InkluSwift</p>
    </footer>
</body>
</html>
