<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Landing</title>
    <!-- Font Awesome untuk ikon aksesibilitas -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- CSS halaman Anda -->
    <link rel="stylesheet" href="{{asset('css/styles.css')}}">
    <!-- Script aksesibilitas -->
    <script src="{{asset('js/accessibility.js')}}"></script>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            color: #333;
            transition: all 0.3s ease;
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
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #FF4B3A;
            color: white;
            border: 2px solid #FF4B3A;
        }

        /* Hero section */
        .hero {
            background-color: #FF4B3A;
            color: white;
            padding: 30px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .hero-content p {
            margin-bottom: 20px;
        }

        .hero-img {
            max-width: 40%;
            padding-top: 4%;
        }

        .search-bar {
            background-color: white;
            border-radius: 10px;
            padding: 20px 20px;
            display: flex;
            width: 100%;
            max-width: 400px;
        }
        
        .search-bar input {
            background-color: #F1EBEB;
            flex: 1;
            border: none;
            padding: 15px 15px;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            gap: 10px;
            margin-right: 10px;
            cursor: pointer; /* Menambahkan cursor pointer untuk menunjukkan elemen bisa diklik */
        }
        
        .search-bar button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
        }

        /* About InkluSwift Section */
        .about-inkluswift {
            padding: 30px 10%;
            text-align: center;
            background-color: white;
        }

        .about-inkluswift h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
            color: #333;
        }

        .about-inkluswift p {
            margin-bottom: 0px;
            max-width: fit-content;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
            font-size: 1rem;
        }
        
        /* How it works section */
        .how-it-works {
            padding: 30px 10%;
            text-align: center;
        }

        .how-it-works h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .step {
            flex: 1;
            min-width: 20px;
            margin-bottom: 20px;
        }


        .step-icon img {
            width: 100px;
            height: 100px;
        }

        .step h3 {
            font-size: 1rem;
            margin-bottom: 0px;
        }

        /* Featured menu section */
        .featured-menu {
            padding: 30px 10%;
            text-align: center;
        }

        .featured-menu h2 {
            margin-bottom: 30px;
            font-size: 1.8rem;
        }

        .carousel {
            position: relative;
            overflow: hidden;
            padding: 0 20px;
        }

        .menu-items {
            display: flex;
            gap: 20px;
            transition: transform 0.5s ease;
            width: fit-content;
        }

        .menu-item {
            min-width: 200px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            flex: 0 0 auto;
        }

        .menu-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .menu-item-info {
            padding: 15px;
            text-align: left;
        }

        .menu-item-info h3 {
            font-size: 1rem;
            margin-bottom: 5px;
        }

        .menu-item-info p {
            color: #FF4B3A;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .order-btn {
            display: block; 
            width: 100%;
            background-color: #ff4d4d;
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 600;
            text-align: center;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: #FF4B3A;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-weight: bold;
            user-select: none;
        }

        .prev {
            left: 0;
        }

        .next {
            right: 0;
        }

        .view-all {
            display: inline-block;
            margin-top: 20px;
            color: #FF4B3A;
            text-decoration: none;
            font-weight: 600;
        }

        /* Categories section */
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
        }

        .category-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .category-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Features section */
        .features {
            background-color: #FF4B3A;
            padding: 40px 10%;
        }

        .features-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .feature-icon img {
            width: 65px;
            height: 65px;
        }

        .feature-text {
            color: #FF4B3A;
            font-weight: 600;
            font-weight: bold;
        }

        /* App download section */
        .app-download {
            background-color: #FF4B3A;
            padding: 40px 10%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .app-screens {
            display: flex;
            gap: 0px;
            max-width: 50%;
        }

        .app-screen img {
            width: 100%;
        }

        .app-info h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .app-badges {
            display: flex;
            gap: 5px;
            margin-top: 20px;
        }

        .app-badge img {
            height: 100px;
            gap: 5px;
        }

        /* Footer */
        footer {
            background-color: #222;
            color: white;
            padding: 20px 10%;
            text-align: center;
        }

        /* Slider wrapper to create the overflow effect */
        .slider-wrapper {
            overflow: hidden;
            position: relative;
            padding: 10px 0;
        }

    </style>
</head>
<body>
    <!-- Header with logo and auth buttons -->
    <header>
        <div class="logo">
            <a href="{{ route('landing') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
                <h1>InkluSwift</h1>
            </a>
        </div>
        <div class="auth-buttons">
            <a href="{{route('login')}}" class="btn btn-primary">Log In</a>
            <a href="{{route('register')}}" class="btn btn-primary">Sign Up</a>
        </div>
    </header>

    <!-- Hero section with search bar -->
    <section class="hero">
        <div class="hero-content">
            <h1>Semuanya Bisa,<br>Semuanya Terlayani</h1>
            <p>Pesan makananmu sekarang!</p>
            <div class="search-bar">
                <input type="text" placeholder="Cari Makanan" onclick="window.location.href='{{route('login')}}'">
                <button onclick="window.location.href='{{route('login')}}'">Cari</button>
            </div>
        </div>
        <img src="{{asset('Assets/mam.png')}}" alt="Food display" class="hero-img">
    </section>

    <!-- About InkluSwift Section -->
    <section class="about-inkluswift">
        <h2>Apa Itu InkluSwift?</h2>
        <p>
            InkluSwift adalah platform pesan antar makanan yang didesain khusus untuk memenuhi kebutuhan semua pengguna, termasuk mereka dengan disabilitas visual. Dengan teknologi yang ramah aksesibilitas, kami memastikan semua orang dapat menikmati pengalaman memesan makanan dengan mudah, cepat, dan tanpa hambatan. Karena kemudahan pesan antar seharusnya bisa dinikmati siapa saja!
        </p>
    </section>

    <!-- How it works section -->
    <section class="how-it-works">
        <h2>Bagaimana Cara Kerjanya?</h2>
        <div class="steps">
            <div class="step">
                <div class="step-icon">
                    <img src="{{asset('Assets/reci.png')}}" alt="Menu icon">
                </div>
                <h3>Pilih Menu</h3>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="{{asset('Assets/bill.png')}}" alt="Payment icon">
                </div>
                <h3>Lakukan Pembayaran</h3>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="{{asset('Assets/eat.png')}}" alt="Food icon">
                </div>
                <h3>Makanan Siap Dinikmati</h3>
            </div>
        </div>
    </section>

    <!-- Featured menu section -->
    <section class="featured-menu">
        <h2>Menu Terlaris</h2>
        <div class="carousel">
            <div class="slider-wrapper">
                <div class="menu-items">
                    <div class="menu-item">
                        <img src="{{asset('Assets/burger.png')}}" alt="Cheese Burger">
                        <div class="menu-item-info">
                            <h3>Cheese Burger</h3>
                            <p>Rp28.000</p>
                            <a href="{{route('login')}}" class="order-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                    <div class="menu-item">
                        <img src="{{asset('Assets/toffe.png')}}" alt="Toffe's Cake">
                        <div class="menu-item-info">
                            <h3>Toffe's Cake</h3>
                            <p>Rp25.000</p>
                            <a href="{{route('login')}}" class="order-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                    <div class="menu-item">
                        <img src="{{asset('Assets/pancake.png')}}" alt="Pancake">
                        <div class="menu-item-info">
                            <h3>Pancake</h3>
                            <p>Rp24.999</p>
                            <a href="{{route('login')}}" class="order-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                    <div class="menu-item">
                        <img src="{{asset('Assets/sandwich.png')}}" alt="Crispy Sandwich">
                        <div class="menu-item-info">
                            <h3>Crispy Sandwich</h3>
                            <p>Rp18.499</p>
                            <a href="{{route('login')}}" class="order-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                    <div class="menu-item">
                        <img src="{{asset('Assets/soup.png')}}" alt="Thai Soup">
                        <div class="menu-item-info">
                            <h3>Thai Soup</h3>
                            <p>Rp21.000</p>
                            <a href="{{route('login')}}" class="order-btn">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-nav prev">&lt;</div>
            <div class="carousel-nav next">&gt;</div>
        </div>
        <a href="{{route('login')}}" class="view-all">Lihat Semua &gt;</a>
    </section>

    <!-- Categories section -->
    <section class="categories">
        <h2>Cari berdasarkan Kategori</h2>
        <div class="category-list">
            <a href="{{route('login')}}" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/makanan.png')}}" alt="Makanan">
                </div>
                <h3>Makanan</h3>
            </a>
            <a href="{{route('login')}}" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/minuman.jpg')}}" alt="Minuman">
                </div>
                <h3>Minuman</h3>
            </a>
            <a href="{{route('login')}}" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/camilan.png')}}" alt="Camilan">
                </div>
                <h3>Camilan</h3>
            </a>
        </div>
    </section>

    <!-- Features section -->
    <section class="features">
        <div class="features-container">
            <div class="feature">
                <div class="feature-icon">
                    <img src="{{asset('Assets/disa.png')}}" alt="Accessibility icon">
                </div>
                <div class="feature-text">Ramah Disabilitas</div>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <img src="{{('Assets/menuu.png')}}" alt="Quality icon">
                </div>
                <div class="feature-text">Menu yang Berkualitas</div>
            </div>
            <div class="feature">
                <div class="feature-icon">
                    <img src="{{asset('Assets/timee.png')}}" alt="Fast delivery icon">
                </div>
                <div class="feature-text">Pengiriman Cepat</div>
            </div>
        </div>
    </section>

    <!-- App download section -->
    <section class="app-download">
        <div class="app-screens">
            <div class="app-screen">
                <img src="{{asset('Assets/homemock.png')}}" alt="App screenshot 1">
            </div>
            <div class="app-screen">
                <img src="{{asset('Assets/startmock.png')}}" alt="App screenshot 2">
            </div>
        </div>
        <div class="app-info">
            <h2>Download juga Aplikasinya!</h2>
            <div class="app-badges">
                <a href="https://play.google.com/" class="app-badge">
                    <img src="{{asset('Assets/Google Play Download.png')}}" alt="Google Play">
                </a>
                <a href="https://www.apple.com/id/app-store/" class="app-badge">
                    <img src="{{asset('Assets/App Store Download Button.png')}}" alt="App Store">
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>All Rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Slider functionality
            const menuItems = document.querySelector('.menu-items');
            const prevBtn = document.querySelector('.prev');
            const nextBtn = document.querySelector('.next');
            
            // Calculate item width including margin
            const menuItem = document.querySelector('.menu-item');
            const itemStyle = window.getComputedStyle(menuItem);
            const itemWidth = menuItem.offsetWidth + parseInt(itemStyle.marginRight) + 20; // 20px is the gap
            
            // Set total number of items and current position
            const totalItems = document.querySelectorAll('.menu-item').length;
            let currentPosition = 0;
            let itemsPerView = Math.floor(document.querySelector('.slider-wrapper').offsetWidth / itemWidth);
            
            // Initialize slider position
            updateSliderPosition();
            
            // Add event listeners for buttons
            prevBtn.addEventListener('click', function() {
                if (currentPosition > 0) {
                    currentPosition--;
                    updateSliderPosition();
                }
            });
            
            nextBtn.addEventListener('click', function() {
                if (currentPosition < totalItems - itemsPerView) {
                    currentPosition++;
                    updateSliderPosition();
                }
            });
            
            // Update slider position based on current item
            function updateSliderPosition() {
                const translateValue = -currentPosition * itemWidth;
                menuItems.style.transform = `translateX(${translateValue}px)`;
                
                // Update button states
                prevBtn.style.opacity = currentPosition === 0 ? '0.5' : '1';
                nextBtn.style.opacity = currentPosition >= totalItems - itemsPerView ? '0.5' : '1';
            }
            
            // Handle window resize to recalculate items per view
            window.addEventListener('resize', function() {
                itemsPerView = Math.floor(document.querySelector('.slider-wrapper').offsetWidth / itemWidth);
                
                // Make sure current position is valid after resize
                if (currentPosition > totalItems - itemsPerView) {
                    currentPosition = Math.max(0, totalItems - itemsPerView);
                }
                
                updateSliderPosition();
            });
        });
    </script>
</body>
</html>