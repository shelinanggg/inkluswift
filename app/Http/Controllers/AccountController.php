<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Menampilkan halaman account management
     */
    public function index()
    {
        return view('account');
    }

    /**
     * Mendapatkan daftar user dengan pagination
     */
    public function getUsers(Request $request)
    {
        $search = $request->search ?? '';
        $role = $request->role ?? '';
        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;

        $query = User::query();

        // Filter berdasarkan search
        if (!empty($search)) {
            $query->where('user_id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter berdasarkan role
        if (!empty($role)) {
            $query->where('role', $role);
        }

        // Total data
        $totalItems = $query->count();

        // Get data dengan pagination
        $users = $query->orderBy('join_date', 'desc')
                      ->skip(($page - 1) * $perPage)
                      ->take($perPage)
                      ->get();

        return response()->json([
            'users' => $users,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Menyimpan user baru
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users,email|max:100',
            'password' => 'required|string|min:6|max:20',
            'phone' => 'nullable|string|max:13',
            'role' => 'required|in:admin,staff,customer',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate user_id
        $lastUser = User::orderBy('user_id', 'desc')->first();
        $userIdNumber = $lastUser ? (int)substr($lastUser->user_id, 1) + 1 : 1;
        $userId = 'U' . str_pad($userIdNumber, 4, '0', STR_PAD_LEFT);

        // Upload gambar jika ada
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        // Buat user baru
        $user = User::create([
            'user_id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'address' => $request->address,
            'profile_picture' => $profilePicturePath,
            'join_date' => now()->format('Y-m-d'),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Mendapatkan detail user
     */
    public function show($id)
    {
        $user = User::where('user_id', $id)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['user' => $user]);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        // Cari user
        $user = User::where('user_id', $id)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:60',
            'email' => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6|max:20',
            'phone' => 'nullable|string|max:13',
            'role' => 'required|in:admin,staff,customer',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Data untuk update
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
            'address' => $request->address,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $userData['password'] = $request->password;
        }

        // Upload gambar jika ada
        if ($request->hasFile('profile_picture')) {
            // Hapus gambar lama jika ada
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Upload gambar baru
            $userData['profile_picture'] = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        // Update user
        $user->update($userData);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::where('user_id', $id)->first();
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Hapus gambar jika ada
        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Hapus user
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}