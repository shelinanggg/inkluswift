<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display cart items for current user
     */
    public function index()
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        // Ambil data cart dengan relasi menu
        $cartItems = Cart::with('menu')
            ->where('user_id', $userId)
            ->get();

        // Hitung total
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->subtotal_after_discount;
        });

        return view('cart', compact('cartItems', 'totalAmount'));
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'menu_id' => 'required|string|exists:menus,menu_id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Session::get('user_id');
        $menuId = $request->menu_id;
        $quantity = $request->quantity;

        // Ambil data menu
        $menu = Menu::where('menu_id', $menuId)->first();
        
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ], 404);
        }

        try {
            DB::transaction(function () use ($userId, $menuId, $quantity, $menu) {
                // Cek apakah item sudah ada di cart
                $existingCart = Cart::where('user_id', $userId)
                    ->where('menu_id', $menuId)
                    ->first();

                if ($existingCart) {
                    // Update quantity jika item sudah ada
                    $existingCart->quantity += $quantity;
                    $existingCart->save();
                } else {
                    // Tambah item baru ke cart
                    Cart::create([
                        'user_id' => $userId,
                        'menu_id' => $menuId,
                        'quantity' => $quantity,
                        'price' => $menu->price
                    ]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item ke keranjang'
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(Request $request)
    {
        if (!Session::has('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'cart_id' => 'required|integer|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Session::get('user_id');
        $cartId = $request->cart_id;
        $quantity = $request->quantity;

        try {
            $cartItem = Cart::where('id', $cartId)
                ->where('user_id', $userId)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }

            $cartItem->quantity = $quantity;
            $cartItem->save();

            // Hitung subtotal baru
            $subtotal = $cartItem->subtotal_after_discount;

            return response()->json([
                'success' => true,
                'message' => 'Quantity berhasil diupdate',
                'subtotal' => $subtotal
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate quantity'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request)
    {
        if (!Session::has('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'cart_id' => 'required|integer|exists:carts,id'
        ]);

        $userId = Session::get('user_id');
        $cartId = $request->cart_id;

        try {
            $cartItem = Cart::where('id', $cartId)
                ->where('user_id', $userId)
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang'
            ], 500);
        }
    }

    /**
     * Clear all cart items for current user
     */
    public function clearCart()
    {
        if (!Session::has('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $userId = Session::get('user_id');

        try {
            Cart::where('user_id', $userId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang'
            ], 500);
        }
    }

    /**
     * Get cart count for current user
     */
    public function getCartCount()
    {
        if (!Session::has('is_logged_in')) {
            return response()->json(['count' => 0]);
        }

        $userId = Session::get('user_id');
        $count = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json(['count' => $count]);
    }

    /**
     * Get cart items for checkout
     */
    public function getCartForCheckout()
    {
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        $cartItems = Cart::with('menu')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong');
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->subtotal_after_discount;
        });

        return view('cart.checkout', compact('cartItems', 'totalAmount'));
    }

    /**
     * Helper method to check if user is logged in
     */
    private function isLoggedIn()
    {
        return Session::has('is_logged_in') && Session::get('is_logged_in') === true;
    }

    /**
     * Helper method to get current user ID
     */
    private function getCurrentUserId()
    {
        return Session::get('user_id');
    }
}