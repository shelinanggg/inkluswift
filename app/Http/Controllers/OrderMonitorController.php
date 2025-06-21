<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
     * Update status pesanan
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        
        $order->updateStatus($request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diupdate',
            'order' => $order
        ]);
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