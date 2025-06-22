<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderMonitorController extends Controller
{
    /**
     * Dashboard monitoring pesanan
     */
    public function index()
    {
        // Pesanan hari ini
        $todayOrders = Order::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        // Pendapatan hari ini
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('final_amount');

        // Statistik status pesanan hari ini
        $orderStats = Order::whereDate('created_at', today())
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        return view('orders.monitor', compact('todayOrders', 'todayRevenue', 'orderStats'));
    }

    /**
     * API untuk mendapatkan pesanan terbaru (untuk refresh otomatis)
     */
    public function getLatestOrders()
    {
        $orders = Order::with(['user', 'payment', 'orderItems'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($orders);
    }

    /**
     * Update status pesanan - FIXED VERSION
     */
    public function updateStatus(Request $request, $orderId)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak valid',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Cari order berdasarkan order_id
            $order = Order::where('order_id', $orderId)->first();
            
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan'
                ], 404);
            }

            // Cek apakah status bisa diubah
            $validTransitions = $this->getValidStatusTransitions($order->status);
            $newStatus = $request->status;

            if (!in_array($newStatus, $validTransitions)) {
                return response()->json([
                    'success' => false,
                    'message' => "Status tidak bisa diubah dari {$order->status} ke {$newStatus}"
                ], 400);
            }

            // Update status menggunakan method yang sudah ada
            $updated = $order->updateStatus($newStatus);

            if ($updated) {
                // Reload order untuk mendapatkan data terbaru
                $order = $order->fresh();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Status pesanan berhasil diupdate',
                    'order' => [
                        'order_id' => $order->order_id,
                        'status' => $order->status,
                        'status_label' => $order->status_label,
                        'status_color' => $order->status_color,
                        'confirmed_at' => $order->confirmed_at,
                        'completed_at' => $order->completed_at,
                        'updated_at' => $order->updated_at
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate status pesanan'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Error updating order status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mendapatkan status transisi yang valid
     */
    private function getValidStatusTransitions($currentStatus)
    {
        $transitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed', 'cancelled'],
            'completed' => [], // Status final
            'cancelled' => []  // Status final
        ];

        return $transitions[$currentStatus] ?? [];
    }

    /**
     * Detail pesanan
     */
    public function show($orderId)
    {
        $order = Order::with(['user', 'payment', 'orderItems.menu'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        return view('orders.detail', compact('order'));
    }

    /**
     * Pendapatan harian
     */
    public function dailyRevenue(Request $request)
    {
        $date = $request->get('date', today());
        
        // Total pendapatan per hari
        $dailyRevenue = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->sum('final_amount');

        // Detail pesanan per hari
        $dailyOrders = Order::whereDate('created_at', $date)
            ->where('status', 'completed')
            ->with('orderItems')
            ->get();

        // Jumlah pesanan
        $totalOrders = $dailyOrders->count();

        // Rata-rata nilai pesanan
        $avgOrderValue = $totalOrders > 0 ? $dailyRevenue / $totalOrders : 0;

        return view('orders.daily-revenue', compact(
            'dailyRevenue', 
            'dailyOrders', 
            'totalOrders', 
            'avgOrderValue', 
            'date'
        ));
    }

    /**
     * Statistik minggu ini
     */
    public function weeklyStats()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'completed')
            ->sum('final_amount');

        $weeklyOrders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->count();

        // Revenue per hari dalam seminggu
        $dailyRevenue = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', 'completed')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'weekly_revenue' => $weeklyRevenue,
            'weekly_orders' => $weeklyOrders,
            'daily_breakdown' => $dailyRevenue
        ]);
    }
}