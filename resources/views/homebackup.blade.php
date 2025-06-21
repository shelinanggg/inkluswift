<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift User Dashboard</title>
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

        /* Location display styles */
        .location-display {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            padding: 8px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 0 20px;
        }

        .location-display i {
            color: #FF4B3A;
            margin-right: 8px;
            font-size: 16px;
        }

        #user-location {
            font-weight: 500;
            margin-right: 10px;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .location-btn {
            background-color: #FF4B3A;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .location-btn:hover {
            background-color: #e63c3c;
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

        /* Food Items Section */
        .food-items {
            padding: 20px 40px;
        }

        .food-items h2 {
            font-size: 1.8rem;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .food-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .food-card {
            display: block; /* Untuk anchor */
            text-decoration: none; /* Menghilangkan garis bawah */
            color: inherit; /* Mempertahankan warna teks asli */
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .food-card-link {
            text-decoration: none;
        }
        
        .food-img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }
        
        .food-info {
            padding: 15px;
        }
        
        .food-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .food-price {
            font-size: 14px;
            color: #ff4d4d;
            margin-bottom: 10px;
            font-weight: bold;
        }

        /* Footer */
        footer {
            background-color: #222;
            color: white;
            padding: 20px 10%;
            text-align: center;
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

        <div class="location-display">
            <i class="fas fa-map-marker-alt"></i>
            <span id="user-location">Lokasi anda</span>
            <button id="change-location" class="location-btn">Input</button>
        </div>

        <div class="auth-buttons">
            <a href="{{route('cart')}}" class="btn btn-primary">
                <img src="{{asset('Assets/cart.png')}}" alt="Cart">
                Keranjang</a>
            <a href="{{route('profile')}}" class="btn btn-primary">
                <img src="{{asset('Assets/profile.png')}}" alt="Profile">
                Profil</a>
        </div>
    </header>

    <!-- Hero section with search bar -->
    <section class="hero">
        <div class="hero-content">
            <h1>Mau makan apa hari ini?</h1>
            <p>Pesan makanan favoritmu di sini!</p>
            <div class="search-bar">
                <input type="text" placeholder="Cari Makanan">
                <button>Cari</button>
            </div>
        </div>
        <img src="{{asset('Assets/mam.png')}}" alt="Food display" class="hero-img">
    </section>

    <!-- Categories section -->
    <section class="categories">
        <h2>Cari berdasarkan Kategori</h2>
        <div class="category-list">
            <a href="#" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/makanan.png')}}" alt="Makanan">
                </div>
                <h3>Makanan</h3>
            </a>
            <a href="#" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/minuman.jpg')}}" alt="Minuman">
                </div>
                <h3>Minuman</h3>
            </a>
            <a href="#" class="category">
                <div class="category-img">
                    <img src="{{asset('Assets/camilan.png')}}" alt="Camilan">
                </div>
                <h3>Camilan</h3>
            </a>
        </div>
    </section>

    <!-- Food Items Section -->
    <section class="food-items">
        <h2>Daftar Menu</h2>
        <div class="food-grid">
            <a href="#" class="food-card-link" onclick="setFoodData('cheese_burger', 'Cheese Burger', 'Rp35.000', 'Rp40.000', 'Cheese Burger adalah burger lezat dengan patty daging sapi panggang yang juicy, dilengkapi dengan keju leleh, selada segar, tomat, acar, dan saus spesial di antara roti burger yang lembut.', 'Roti burger, patty daging sapi, keju cheddar, selada, tomat, acar, saus spesial.', 'Sebaiknya, Cheese Burger dikonsumsi segera setelah diterima.', 'Assets/cheese burger.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/cheese burger.jpg')}}" alt="Cheese Burger" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Cheese Burger</h4>
                        <p class="food-price">Rp35.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('nasi_ayam_geprek', 'Nasi Ayam Geprek', 'Rp25.000', 'Rp27.500', 'Ayam goreng tepung yang digeprek dengan sambal pedas, disajikan dengan nasi hangat.', 'Ayam filet, tepung bumbu, cabai rawit, bawang putih, garam, nasi putih.', 'Simpan ayam matang di kulkas (≤4°C), sambal dalam wadah tertutup.', 'Assets/geprek.png')">
                <div class="food-card">
                    <img src="{{asset('Assets/geprek.png')}}" alt="Ayam Geprek" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Nasi Ayam Geprek</h4>
                        <p class="food-price">Rp25.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('mi_goreng_spesial', 'Mie Goreng Spesial', 'Rp22.000', 'Rp24.000', 'Mie goreng dengan topping lengkap seperti ayam, telur, dan sayuran segar.', 'Mie telur, ayam suwir, telur, sawi, kol, kecap manis, bawang putih.', 'Simpan bahan mentah di kulkas, mie matang dikonsumsi dalam 1 hari.', 'Assets/migoreng.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/migoreng.jpg')}}" alt="Mie goreng" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Mie Goreng Spesial</h4>
                        <p class="food-price">Rp22.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('nasi_goreng kampung', 'Nasi Goreng Kampung', 'Rp20.000', 'Rp21.000', 'Nasi goreng khas kampung dengan rasa gurih dan pedas, disajikan dengan telur.', 'Nasi putih, bawang merah, bawang putih, cabai, telur, kecap asin, terasi.', 'Simpan nasi matang di kulkas, hangatkan sebelum dikonsumsi.', 'Assets/nasi goreng.png')">
                <div class="food-card">
                    <img src="{{asset('Assets/nasi goreng.png')}}" alt="Nasi goreng" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Nasi Goreng Kampung</h4>
                        <p class="food-price">Rp20.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('ayam_bakar_madu', 'Ayam Bakar Madu', 'Rp30.000', 'Rp35.000', 'Ayam bakar empuk dengan saus madu manis pedas, disajikan dengan nasi dan lalapan.', 'Ayam, madu, kecap manis, bawang putih, ketumbar, nasi putih, lalapan.', 'Simpan ayam yang sudah dimarinasi di kulkas, habiskan dalam 2 hari.', 'Assets/ayam bakar.png')">
                <div class="food-card">
                    <img src="{{asset('Assets/ayam bakar.png')}}" alt="Ayam Bakar Madu" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Ayam Bakar Madu</h4>
                        <p class="food-price">Rp30.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('bakso', 'Bakso', 'Rp13.000', 'Rp15.000', 'Bakso kenyal dalam kuah kaldu sapi pedas, disajikan dengan mi bihun dan sayuran.', 'Bakso sapi, kaldu sapi, cabai rawit, bawang putih, seledri, bihun.', 'Simpan bakso dan kuah terpisah di kulkas, panaskan sebelum disajikan.', 'Assets/bakso.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/bakso.jpg')}}" alt="Bakso" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Bakso</h4>
                        <p class="food-price">Rp13.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('kentang_goreng', 'Kentang Goreng', 'Rp23.000', 'Rp23.999', 'Kentang goreng renyah, disajikan dengan saus sambal atau mayones.', 'Kentang, garam, minyak goreng.', ' Simpan kentang potong di freezer; goreng saat akan disajikan.', 'Assets/kentangg.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/kentangg.jpg')}}" alt="Kentang" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Kentang Goreng</h4>
                        <p class="food-price">Rp23.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('sosis_bakar', 'Sosis Bakar', 'Rp11.000', 'Rp13.000', 'Sosis bakar dengan olesan saus manis pedas yang menggoda.', 'Sosis ayam/sapi, saus BBQ, kecap manis, cabai bubuk.', 'Simpan sosis di kulkas atau freezer, habiskan dalam 3 hari setelah dibuka.', 'Assets/sosiss.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/sosiss.jpg')}}" alt="Sosis" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Sosis Bakar</h4>
                        <p class="food-price">Rp11.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('cireng_ayam_suwir', 'Cireng Ayam Suwir', 'Rp12.000', 'Rp13.000', 'Cireng kenyal dengan isian ayam suwir pedas, cocok untuk cemilan sore.', 'Tepung tapioka, bawang putih, ayam suwir, cabai, daun bawang.', 'Simpan adonan cireng mentah di freezer, goreng saat akan dikonsumsi.', 'Assets/cireng.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/cireng.jpg')}}" alt="Cireng" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Cireng Ayam Suwir</h4>
                        <p class="food-price">Rp12.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('tahu_crispy', 'Tahu Crispy', 'Rp10.000', 'Rp12.000', 'Tahu goreng dengan lapisan renyah di luar, lembut di dalam.', 'Tahu putih, tepung bumbu, bawang putih bubuk.', 'Simpan tahu dalam kulkas, goreng saat akan disajikan.', 'Assets/tahu crispy.png')">
                <div class="food-card">
                    <img src="{{asset('Assets/tahu crispy.png')}}" alt="Tahu Crispy" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Tahu Crispy</h4>
                        <p class="food-price">Rp10.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('piscok_keju', 'Pisang Coklat Keju', 'Rp14.000', 'Rp20.000', 'Pisang goreng isi coklat dan taburan keju yang manis legit.', 'Pisang kepok, coklat batang, keju parut, tepung, susu kental manis.', 'Simpan pisang kupas di kulkas, olah saat akan disajikan.', 'Assets/piscok.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/piscok.jpg')}}" alt="Piscok" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Pisang Coklat</h4>
                        <p class="food-price">Rp14.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('risma', 'Risol Mayo', 'Rp5.000', 'Rp6.500', 'Risol isi smoked beef, keju dan saus mayo, dibalut kulit crispy.', 'Kulit risol, smoked beef, mayones, keju, tepung roti.', 'Simpan dalam freezer, goreng tanpa dicairkan.', 'Assets/risma.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/risma.jpg')}}" alt="Risol Mayo" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Risol Mayo</h4>
                        <p class="food-price">Rp5.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('es_teh', 'Es Teh', 'Rp6.000', 'Rp7.000', 'Teh segar dengan gula dan es batu, cocok untuk pelepas dahaga.', 'Teh hitam, gula pasir, air, es batu.', 'Simpan teh dalam kulkas, konsumsi dalam 1 hari.', 'Assets/teh.jpg')">
                <div class="food-card">
                    <img src="{{asset('Assets/teh.jpg')}}" alt="Teh" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">EsTeh</h4>
                        <p class="food-price">Rp6.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('jus_alpukat', 'Jus Alpukat', 'Rp15.000', 'Rp16.500', 'Jus alpukat kental dengan campuran coklat dan susu kental manis.', 'Alpukat matang, susu kental manis, coklat cair, es batu.', 'Simpan alpukat di suhu ruang atau kulkas, jus harus disajikan segar.', 'Assets/alpukat.jpg')">
                <div class="food-card">
                    <img src="{{('Assets/alpukat.jpg')}}" alt="Jus Alpukat" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Jus Alpukat</h4>
                        <p class="food-price">Rp15.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('es_coklat', 'Es Coklat', 'Rp12.000', 'Rp13.000', 'Minuman coklat pekat dan dingin, cocok untuk pecinta manis.', 'Coklat bubuk/cair, susu, es batu, gula.', 'Simpan coklat cair di kulkas, buat minuman saat akan disajikan.', 'Assets/coklat.jpg')">
                <div class="food-card">
                    <img src="{{('Assets/coklat.jpg')}}" alt="Es Coklat" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Es Coklat</h4>
                        <p class="food-price">Rp12.000</p>
                    </div>
                </div>
            </a>
            <a href="#" class="food-card-link" onclick="setFoodData('kopsu_aren', 'Kopi Susu Gula Aren', 'Rp14.000', 'Rp20.000', 'Perpaduan kopi robusta dengan susu segar dan gula aren cair.', 'NKopi robusta, susu UHT, gula aren cair, es batu.', 'Simpan kopi dan gula aren dalam botol tertutup di kulkas.', 'Assets/kopi.jpg')">
                <div class="food-card">
                    <img src="{{('Assets/kopi.jpg')}}" alt="Kopi" class="food-img">
                    <div class="food-info">
                        <h4 class="food-name">Kopi Susu Gula Aren</h4>
                        <p class="food-price">Rp14.000</p>
                    </div>
                </div>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>All Rights Reserved © 2025, InkluSwift</p>
    </footer>

    <script>
        function setFoodData(id, title, price, originalPrice, description, ingredients, storage, image) {
            const foodData = {
                id: id,
                title: title,
                price: price,
                originalPrice: originalPrice,
                description: description,
                ingredients: ingredients,
                storage: storage,
                image: image
            };
            localStorage.setItem('foodData', JSON.stringify(foodData));
            window.location.href = "{{route('description')}}";
        }

        // Location functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userLocationSpan = document.getElementById('user-location');
            const changeLocationBtn = document.getElementById('change-location');
            
            
            
            // Update the location display
            function updateLocationDisplay() {
                const location = getLocationFromStorage() || 'Lokasi Anda';
                userLocationSpan.textContent = location;
                localStorage.setItem('userLocation', location);
            }
            
            // Change location button functionality
            changeLocationBtn.addEventListener('click', function() {
                const newLocation = prompt('Masukkan lokasi Anda:', userLocationSpan.textContent);
                if (newLocation && newLocation.trim() !== '') {
                    userLocationSpan.textContent = newLocation.trim();
                    localStorage.setItem('userLocation', newLocation.trim());
                }
            });
            
            // Initialize location on page load
            updateLocationDisplay();
        });
    </script>
</body>
</html>