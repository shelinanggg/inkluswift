<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        
        // Ambil cart items
        $cartItems = Cart::with('menu')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong');
        }

        // Ambil metode pembayaran
        $paymentMethods = PaymentMethod::all();

        // Hitung total
        $totalAmount = $cartItems->sum('subtotal');
        $totalDiscount = $cartItems->sum(function ($item) {
            return $item->subtotal - $item->subtotal_after_discount;
        });
        $subtotalAfterDiscount = $cartItems->sum('subtotal_after_discount');
        $serviceCharge = 10000; // Default service charge
        $finalAmount = $subtotalAfterDiscount + $serviceCharge;

        return view('checkout', compact(
            'cartItems', 
            'paymentMethods', 
            'totalAmount', 
            'totalDiscount', 
            'subtotalAfterDiscount',
            'serviceCharge',
            'finalAmount'
        ));
    }

    /**
     * Process checkout
     */
    public function process(Request $request)
    {
        if (!Session::has('is_logged_in')) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $request->validate([
            'method_id' => 'required|string|exists:payments,method_id',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $userId = Session::get('user_id');
        
        // Cek cart tidak kosong
        $cartItems = Cart::with('menu')
            ->where('user_id', $userId)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja kosong'
            ], 400);
        }

        // Ambil payment method
        $paymentMethod = PaymentMethod::find($request->method_id);
        
        try {
            DB::transaction(function () use ($request, $userId, $cartItems, $paymentMethod) {
                // Hitung total
                $totalAmount = $cartItems->sum('subtotal');
                $totalDiscount = $cartItems->sum(function ($item) {
                    return $item->subtotal - $item->subtotal_after_discount;
                });
                $subtotalAfterDiscount = $cartItems->sum('subtotal_after_discount');
                $serviceCharge = 10000; // Default service charge
                $finalAmount = $subtotalAfterDiscount + $serviceCharge;

                // Handle upload bukti pembayaran
                $proofImage = null;
                if ($request->hasFile('proof_image')) {
                    $proofImage = $request->file('proof_image')->store('payment_proofs', 'public');
                }

                // Buat order
                $order = Order::create([
                    'user_id' => $userId,
                    'method_id' => $request->method_id,
                    'total_amount' => $totalAmount,
                    'total_discount' => $totalDiscount,
                    'service_charge' => $serviceCharge,
                    'final_amount' => $finalAmount,
                    'customer_name' => $request->customer_name,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $request->customer_address,
                    'status' => $paymentMethod->auto_confirm ? 'confirmed' : 'pending',
                    'notes' => $request->notes,
                    'proof_image' => $proofImage,
                    'confirmed_at' => $paymentMethod->auto_confirm ? now() : null,
                ]);

                // Buat order items dari cart
                foreach ($cartItems as $cartItem) {
                    $menu = $cartItem->menu;
                    $discountPercent = $menu->discount ?? 0;
                    
                    $itemCalculation = OrderItem::calculateItemTotal(
                        $cartItem->quantity,
                        $cartItem->price,
                        $discountPercent
                    );

                    OrderItem::create([
                        'order_id' => $order->order_id,
                        'menu_id' => $cartItem->menu_id,
                        'menu_name' => $menu->menu_name,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'discount_percent' => $discountPercent,
                        'subtotal' => $itemCalculation['subtotal'],
                        'discount_amount' => $itemCalculation['discount_amount'],
                        'subtotal_after_discount' => $itemCalculation['subtotal_after_discount'],
                    ]);
                }

                // Kosongkan cart
                Cart::where('user_id', $userId)->delete();

                // Store order ID di session untuk redirect
                Session::put('last_order_id', $order->order_id);
            });

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'redirect_url' => route('checkout.success')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show success page
     */
    public function success()
    {
        if (!Session::has('is_logged_in') || !Session::has('last_order_id')) {
            return redirect()->route('home');
        }

        $orderId = Session::get('last_order_id');
        $order = Order::with(['orderItems', 'payment'])
            ->where('order_id', $orderId)
            ->first();

        if (!$order) {
            return redirect()->route('home');
        }

        // Remove order ID from session
        Session::forget('last_order_id');

        return view('checkout.success', compact('order'));
    }

    /**
     * Get payment method details
     */
    public function getPaymentMethod($methodId)
    {
        $payment = PaymentMethod::find($methodId);
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Metode pembayaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'method_id' => $payment->method_id,
                'method_name' => $payment->method_name,
                'description' => $payment->description,
                'need_proof' => $payment->need_proof,
                'is_cod' => $payment->is_cod,
                'static_proof_url' => $payment->static_proof_url,
                'destination_account' => $payment->destination_account,
                'instructions' => $payment->getInstructions()
            ]
        ]);
    }

    /**
     * Validate checkout data before processing
     */
    private function validateCheckoutData($cartItems, $paymentMethod)
    {
        // Cek stock availability (jika ada field stock di menu)
        foreach ($cartItems as $item) {
            if (isset($item->menu->stock) && $item->menu->stock < $item->quantity) {
                throw new \Exception("Stock {$item->menu->menu_name} tidak mencukupi");
            }
        }

        // Validasi minimum order (jika ada)
        $subtotalAfterDiscount = $cartItems->sum('subtotal_after_discount');
        $serviceCharge = 10000;
        $finalAmount = $subtotalAfterDiscount + $serviceCharge;
        $minimumOrder = 15000; // 15k minimum order (sudah termasuk service charge)
        
        if ($finalAmount < $minimumOrder) {
            throw new \Exception("Minimum order Rp " . number_format($minimumOrder, 0, ',', '.') . " (sudah termasuk biaya layanan)");
        }

        return true;
    }
}