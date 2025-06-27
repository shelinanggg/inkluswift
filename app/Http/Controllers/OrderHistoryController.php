<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderHistoryController extends Controller
{
    /**
     * Tampilkan histori pemesanan user
     */
    public function index(Request $request)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        // Ambil data user untuk sidebar
        $user = User::findOrFail($userId);
        
        $orders = Order::with(['orderItems', 'payment'])
            ->forUser($userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-history.index', compact('orders', 'user'));
    }

    /**
     * Tampilkan detail pemesanan
     */
    public function show($orderId)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        // Ambil data user untuk sidebar
        $user = User::findOrFail($userId);
        
        $order = Order::with(['orderItems.menu', 'payment'])
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return view('order-history.detail', compact('order', 'user'));
    }

    /**
     * Filter histori berdasarkan status
     */
    public function filterByStatus(Request $request)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        $status = $request->get('status');
        
        // Ambil data user untuk sidebar
        $user = User::findOrFail($userId);
        
        $query = Order::with(['orderItems', 'payment'])
            ->forUser($userId);
            
        if ($status && $status !== 'all') {
            $query->status($status);
        }
        
        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-history.index', compact('orders', 'status', 'user'));
    }

    /**
     * Batalkan pesanan (jika masih bisa)
     */
    public function cancel($orderId)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        $order = Order::where('order_id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (!$order->canBeCancelled()) {
            return redirect()->back()
                ->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $order->updateStatus('cancelled');

        return redirect()->route('order-history.index')
            ->with('success', 'Pesanan berhasil dibatalkan');
    }

    /**
     * Pesan ulang pesanan yang sudah pernah dipesan
     */
    public function reorder($orderId)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        $order = Order::with('orderItems')
            ->where('order_id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();

        try {
            DB::transaction(function () use ($order, $userId) {
                // Tambahkan setiap item dari order ke cart database
                foreach ($order->orderItems as $item) {
                    // Cek apakah item sudah ada di cart
                    $existingCartItem = Cart::where('user_id', $userId)
                        ->where('menu_id', $item->menu_id)
                        ->first();

                    if ($existingCartItem) {
                        // Jika sudah ada, tambahkan quantity
                        $existingCartItem->quantity += $item->quantity;
                        $existingCartItem->save();
                    } else {
                        // Jika belum ada, buat item cart baru
                        Cart::create([
                            'user_id' => $userId,
                            'menu_id' => $item->menu_id,
                            'quantity' => $item->quantity,
                            'price' => $item->price
                        ]);
                    }
                }
            });

            return redirect()->route('cart')
                ->with('success', 'Item pesanan telah ditambahkan ke keranjang');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan item ke keranjang. Silakan coba lagi.');
        }
    }

    /**
     * Cek apakah user sudah login (helper method)
     */
    public function isLoggedIn()
    {
        return Session::has('is_logged_in') && Session::get('is_logged_in') === true;
    }
}