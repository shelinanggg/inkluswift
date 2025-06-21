<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkluSwift Cart</title>
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

        html, body {
            height: 100%;
        }

        body {
            background-color: #eee;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 20px 10%;
            display: flex;
            gap: 20px;
        }

        .cart-container {
            flex: 2;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .order-summary {
            flex: 1;
            background-color: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .cart-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item-details {
            display: flex;
            align-items: center;
        }

        .item-image {
            width: 40px;
            height: 40px;
            margin-right: 15px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .item-price {
            color: #ff5a46;
            font-size: 14px;
        }

        .item-original-price {
            color: #999;
            font-size: 14px;
            text-decoration: line-through;
            margin-left: 5px;
        }

        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-button {
            background-color: #f0f0f0;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            font-weight: bold;
            cursor: pointer;
        }

        .add-button {
            background-color: #ff5a46;
            color: white;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            font-weight: bold;
            cursor: pointer;
        }

        .remove-button {
        background: none;
        border: none;
        cursor: pointer;
        color:rgb(209, 0, 0);
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin: 20px 0;
        }

        .checkout-button {
            background-color: #ff5a46;
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        footer {
            background-color: #222;
            color: white;
            padding: 20px 40px;
            text-align: center;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="{{asset('Assets/logo hd.png')}}" alt="InkluSwift Logo">
            <h1>InkluSwift</h1>
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

    <div class="main-content">
        <div class="cart-container">
            <h2 class="cart-title">Keranjang saya</h2>

            <div class="cart-item" data-price="35000" data-original-price="40000">
                <div class="item-details">
                    <img src="{{asset('Assets/cheese burger.jpg')}}" alt="Cheese Burger" class="item-image">
                    <div>
                        <div class="item-name">Cheese Burger</div>
                        <div class="item-price">Rp35.000 <span class="item-original-price">Rp40.000</span></div>
                    </div>
                </div>
                <div class="item-quantity">
                    <button class="remove-button" onclick="removeItem(this)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                    <button class="quantity-button" onclick="decreaseQuantity(this)">-</button>
                    <span class="quantity-value">1</span>
                    <button class="add-button" onclick="increaseQuantity(this)">+</button>
                </div>
            </div>
        </div>

        <div class="order-summary">
            <h3 class="summary-title">Detail Pemesanan</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="subtotal">Rp0</span>
            </div>
            <div class="summary-total">
                <span>Total harga</span>
                <span id="total">Rp0</span>
            </div>
            <button class="checkout-button" onclick="window.location.href='{{route('checkout')}}'">Checkout</button>
        </div>
    </div>

    <footer>
        <p>All rights Reserved. © 2025, InkluSwift</p>
    </footer>

    <script>
        function increaseQuantity(button) {
            const quantityElement = button.parentElement.querySelector('.quantity-value');
            let quantity = parseInt(quantityElement.textContent);
            quantity++;
            quantityElement.textContent = quantity;
            updateTotals();
        }

        function decreaseQuantity(button) {
            const quantityElement = button.parentElement.querySelector('.quantity-value');
            let quantity = parseInt(quantityElement.textContent);
            if (quantity > 1) {
                quantity--;
                quantityElement.textContent = quantity;
                updateTotals();
            }
        }

        function removeItem(button) {
            const cartItem = button.closest('.cart-item');
            cartItem.remove();
            updateTotals();
        }

        function updateTotals() {
            let subtotal = 0;
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => {
                const price = parseFloat(item.getAttribute('data-price'));
                const quantity = parseInt(item.querySelector('.quantity-value').textContent);
                subtotal += price * quantity;
            });

            const formattedSubtotal = 'Rp' + subtotal.toLocaleString('id-ID');
            document.getElementById('subtotal').textContent = formattedSubtotal;
            document.getElementById('total').textContent = formattedSubtotal;
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateTotals();
        });
    </script>
</body>
</html>
