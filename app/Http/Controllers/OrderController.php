<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'orderItems.menu']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter berdasarkan customer
        if ($request->filled('customer')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->customer . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->customer . '%');
            });
        }

        // Search berdasarkan order ID
        if ($request->filled('search')) {
            $query->where('order_id', 'like', '%' . $request->search . '%');
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $orders = $query->paginate(15);

        // Statistik untuk dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::status('pending')->count(),
            'confirmed_orders' => Order::status('confirmed')->count(),
            'preparing_orders' => Order::status('preparing')->count(),
            'ready_orders' => Order::status('ready')->count(),
            'completed_orders' => Order::status('completed')->count(),
            'cancelled_orders' => Order::status('cancelled')->count(),
            'total_revenue' => Order::status('completed')->sum('final_amount'),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order
     */
    public function show($orderId)
    {
        $order = Order::with(['user', 'payment', 'orderItems.menu'])
                     ->where('order_id', $orderId)
                     ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $order = Order::where('order_id', $orderId)->firstOrFail();

            // Validasi status transition
            if (!$this->isValidStatusTransition($order->status, $request->status)) {
                return redirect()->back()
                               ->with('error', 'Status transition tidak valid');
            }

            // Update status
            $order->updateStatus($request->status);

            // Update notes jika ada
            if ($request->filled('notes')) {
                $order->notes = $request->notes;
                $order->save();
            }

            DB::commit();

            return redirect()->back()
                           ->with('success', 'Status pesanan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui status pesanan');
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $order = Order::where('order_id', $orderId)->firstOrFail();

            if (!$order->canBeCancelled()) {
                return redirect()->back()
                               ->with('error', 'Pesanan tidak dapat dibatalkan');
            }

            $order->status = 'cancelled';
            $order->notes = 'Dibatalkan oleh admin. Alasan: ' . $request->reason;
            $order->save();

            return redirect()->back()
                           ->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal membatalkan pesanan');
        }
    }

    /**
     * Generate order report
     */
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'status' => 'nullable|in:all,pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $query = Order::with(['user', 'payment', 'orderItems.menu'])
                     ->whereBetween('created_at', [
                         $request->date_from . ' 00:00:00',
                         $request->date_to . ' 23:59:59'
                     ]);

        if ($request->status && $request->status !== 'all') {
            $query->status($request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Hitung statistik
        $reportStats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->where('status', 'completed')->sum('final_amount'),
            'total_discount' => $orders->sum('total_discount'),
            'total_service_charge' => $orders->sum('service_charge'),
            'status_breakdown' => $orders->groupBy('status')->map->count(),
            'daily_revenue' => $orders->where('status', 'completed')
                                    ->groupBy(function($order) {
                                        return $order->created_at->format('Y-m-d');
                                    })
                                    ->map(function($dayOrders) {
                                        return $dayOrders->sum('final_amount');
                                    })
        ];

        return view('orders.report', compact('orders', 'reportStats', 'request'));
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'status' => 'nullable|in:all,pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $query = Order::with(['user', 'payment', 'orderItems.menu'])
                     ->whereBetween('created_at', [
                         $request->date_from . ' 00:00:00',
                         $request->date_to . ' 23:59:59'
                     ]);

        if ($request->status && $request->status !== 'all') {
            $query->status($request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $filename = 'orders_' . $request->date_from . '_to_' . $request->date_to . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Order ID',
                'Tanggal',
                'Customer',
                'Phone',
                'Status',
                'Total Amount',
                'Discount',
                'Service Charge',
                'Final Amount',
                'Payment Method',
                'Items'
            ]);

            // Data orders
            foreach ($orders as $order) {
                $items = $order->orderItems->map(function($item) {
                    return $item->menu_name . ' (x' . $item->quantity . ')';
                })->join(', ');

                fputcsv($file, [
                    $order->order_id,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_phone,
                    $order->status_label,
                    $order->total_amount,
                    $order->total_discount,
                    $order->service_charge,
                    $order->final_amount,
                    $order->payment->method_name ?? 'N/A',
                    $items
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * View order proof of payment
     */
    public function viewProof($orderId)
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();

        if (!$order->proof_image) {
            return redirect()->back()
                           ->with('error', 'Bukti pembayaran tidak tersedia');
        }

        return view('orders.proof', compact('order'));
    }

    /**
     * Bulk update orders status
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_ids' => 'required|array',
            'order_ids.*' => 'required|string|exists:orders,order_id',
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $orders = Order::whereIn('order_id', $request->order_ids)->get();
            $updated = 0;

            foreach ($orders as $order) {
                if ($this->isValidStatusTransition($order->status, $request->status)) {
                    $order->updateStatus($request->status);
                    $updated++;
                }
            }

            DB::commit();

            return redirect()->back()
                           ->with('success', "$updated pesanan berhasil diperbarui");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui pesanan');
        }
    }

    /**
     * Get order statistics for dashboard
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', '7'); // default 7 days

        $startDate = now()->subDays($period);
        $endDate = now();

        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::status('completed')->whereBetween('created_at', [$startDate, $endDate])->sum('final_amount'),
            'average_order_value' => Order::status('completed')->whereBetween('created_at', [$startDate, $endDate])->avg('final_amount'),
            'status_breakdown' => Order::whereBetween('created_at', [$startDate, $endDate])
                                      ->selectRaw('status, count(*) as count')
                                      ->groupBy('status')
                                      ->pluck('count', 'status'),
            'daily_orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                                  ->selectRaw('DATE(created_at) as date, count(*) as count')
                                  ->groupBy('date')
                                  ->orderBy('date')
                                  ->pluck('count', 'date'),
            'daily_revenue' => Order::status('completed')
                                   ->whereBetween('created_at', [$startDate, $endDate])
                                   ->selectRaw('DATE(created_at) as date, sum(final_amount) as revenue')
                                   ->groupBy('date')
                                   ->orderBy('date')
                                   ->pluck('revenue', 'date')
        ];

        return response()->json($stats);
    }

    /**
     * Validate status transition
     */
    private function isValidStatusTransition($currentStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed', 'cancelled'],
            'completed' => [], // No transitions from completed
            'cancelled' => [] // No transitions from cancelled
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}