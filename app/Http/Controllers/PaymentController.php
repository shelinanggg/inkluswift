<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman payment management
     */
    public function index()
    {
        return view('payment');
    }

    /**
     * Mendapatkan daftar payment dengan pagination
     */
    public function getPayments(Request $request)
    {
        $search = $request->search ?? '';
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $query = Payment::query();

        // Filter berdasarkan search
        if (!empty($search)) {
            $query->where('method_id', 'like', "%{$search}%")
                  ->orWhere('method_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('destination_account', 'like', "%{$search}%");
        }

        // Total data
        $totalItems = $query->count();

        // Get data dengan pagination
        $payments = $query->orderBy('method_id', 'asc')
                         ->skip(($page - 1) * $perPage)
                         ->take($perPage)
                         ->get();

        return response()->json([
            'payments' => $payments,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Menyimpan payment method baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'method_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'destination_account' => 'nullable|string|max:100',
            'static_proof' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate method_id
        $lastPayment = Payment::orderBy('method_id', 'desc')->first();
        $methodIdNumber = $lastPayment ? (int)substr($lastPayment->method_id, 1) + 1 : 1;
        $methodId = 'P' . str_pad($methodIdNumber, 4, '0', STR_PAD_LEFT);

        // Upload gambar jika ada
        $staticProofPath = null;
        if ($request->hasFile('static_proof')) {
            $staticProofPath = $request->file('static_proof')->store('payment-proofs', 'public');
        }

        // Buat payment method baru
        $payment = Payment::create([
            'method_id' => $methodId,
            'method_name' => $request->method_name,
            'description' => $request->description,
            'static_proof' => $staticProofPath,
            'destination_account' => $request->destination_account,
        ]);

        return response()->json([
            'message' => 'Payment method created successfully',
            'payment' => $payment
        ], 201);
    }

    /**
     * Mendapatkan detail payment method
     */
    public function show($id)
    {
        $payment = Payment::where('method_id', $id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        return response()->json(['payment' => $payment]);
    }

    /**
     * Update payment method
     */
    public function update(Request $request, $id)
    {
        // Cari payment method
        $payment = Payment::where('method_id', $id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'method_name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'destination_account' => 'nullable|string|max:100',
            'static_proof' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Data untuk update
        $paymentData = [
            'method_name' => $request->method_name,
            'description' => $request->description,
            'destination_account' => $request->destination_account,
        ];

        // Upload gambar jika ada
        if ($request->hasFile('static_proof')) {
            // Hapus gambar lama jika ada
            if ($payment->static_proof) {
                Storage::disk('public')->delete($payment->static_proof);
            }
            
            // Upload gambar baru
            $paymentData['static_proof'] = $request->file('static_proof')->store('payment-proofs', 'public');
        }

        // Update payment method
        $payment->update($paymentData);

        return response()->json([
            'message' => 'Payment method updated successfully',
            'payment' => $payment
        ]);
    }

    /**
     * Hapus payment method
     */
    public function destroy($id)
    {
        $payment = Payment::where('method_id', $id)->first();
        
        if (!$payment) {
            return response()->json(['message' => 'Payment method not found'], 404);
        }

        // Hapus gambar jika ada
        if ($payment->static_proof) {
            Storage::disk('public')->delete($payment->static_proof);
        }

        // Hapus payment method
        $payment->delete();

        return response()->json(['message' => 'Payment method deleted successfully']);
    }
}