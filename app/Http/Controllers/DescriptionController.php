<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class DescriptionController extends Controller
{
    /**
     * Display the menu description page
     */
    public function index(Request $request)
    {
        // Get menu_id from request (could be from query parameter or route parameter)
        $menuId = $request->get('menu_id') ?? $request->route('menu_id');
        
        if (!$menuId) {
            return redirect()->route('home')->with('error', 'Menu tidak ditemukan');
        }

        // Get menu data
        $menu = Menu::where('menu_id', $menuId)->first();
        
        if (!$menu) {
            return redirect()->route('home')->with('error', 'Menu tidak ditemukan');
        }

        // Calculate prices
        $originalPrice = $menu->price;
        $discountedPrice = $originalPrice;
        
        if ($menu->discount > 0) {
            $discountedPrice = $originalPrice - ($originalPrice * $menu->discount / 100);
        }

        return view('description', compact('menu', 'originalPrice', 'discountedPrice'));
    }

    /**
     * Show menu description with specific menu ID
     */
    public function show($menuId)
    {
        $menu = Menu::where('menu_id', $menuId)->first();
        
        if (!$menu) {
            return redirect()->route('home')->with('error', 'Menu tidak ditemukan');
        }

        // Calculate prices
        $originalPrice = $menu->price;
        $discountedPrice = $originalPrice;
        
        if ($menu->discount > 0) {
            $discountedPrice = $originalPrice - ($originalPrice * $menu->discount / 100);
        }

        return view('description', compact('menu', 'originalPrice', 'discountedPrice'));
    }

    /**
     * Add item to cart from description page
     * This method delegates to CartController for consistency
     */
    public function addToCart(Request $request)
    {
        // Check if user is logged in
        if (!$this->isLoggedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        // Validate request
        $request->validate([
            'menu_id' => 'required|string|exists:menus,menu_id',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $userId = $this->getCurrentUserId();
        $menuId = $request->menu_id;
        $quantity = $request->quantity;

        // Get menu data
        $menu = Menu::where('menu_id', $menuId)->first();
        
        if (!$menu) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ], 404);
        }

        try {
            DB::transaction(function () use ($userId, $menuId, $quantity, $menu) {
                // Check if item already exists in cart
                $existingCart = Cart::where('user_id', $userId)
                    ->where('menu_id', $menuId)
                    ->first();

                if ($existingCart) {
                    // Update quantity if item already exists
                    $existingCart->quantity += $quantity;
                    $existingCart->save();
                } else {
                    // Add new item to cart
                    Cart::create([
                        'user_id' => $userId,
                        'menu_id' => $menuId,
                        'quantity' => $quantity,
                        'price' => $menu->price
                    ]);
                }
            });

            // Get updated cart count
            $cartCount = Cart::where('user_id', $userId)->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Error adding to cart from description: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan item ke keranjang'
            ], 500);
        }
    }

    /**
     * Get cart count for navbar update
     */
    public function getCartCount()
    {
        if (!$this->isLoggedIn()) {
            return response()->json(['count' => 0]);
        }

        $userId = $this->getCurrentUserId();
        $count = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json(['count' => $count]);
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