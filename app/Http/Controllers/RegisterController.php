<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:60',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|max:20|confirmed',
            'phone' => 'nullable|string|max:13|regex:/^[0-9+\-\s()]*$/',
            'role' => ['required', Rule::in(['admin', 'staff', 'customer'])],
            'address' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 60 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.max' => 'Password maksimal 20 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'phone.regex' => 'Format nomor telepon tidak valid',
            'phone.max' => 'Nomor telepon maksimal 13 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role yang dipilih tidak valid',
            'address.max' => 'Alamat maksimal 100 karakter',
            'profile_picture.image' => 'File harus berupa gambar',
            'profile_picture.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif',
            'profile_picture.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Generate custom user ID
        $userId = $this->generateUserId();

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Buat user baru
        $user = User::create([
            'user_id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => 'active', // default status
            'address' => $request->address,
            'profile_picture' => $profilePicturePath,
            'join_date' => now()->toDateString(),
        ]);

        // Redirect setelah registrasi berhasil
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
    }

    /**
     * Generate unique user ID (5 characters)
     */
    private function generateUserId()
    {
        // Ambil user terakhir berdasarkan user_id
        $lastUser = User::orderBy('user_id', 'desc')->first();
        
        // Generate user_id baru
        $userIdNumber = $lastUser ? (int)substr($lastUser->user_id, 1) + 1 : 1;
        $userId = 'U' . str_pad($userIdNumber, 4, '0', STR_PAD_LEFT);

        // Pastikan user_id unik (jika ada duplikasi)
        while (User::where('user_id', $userId)->exists()) {
            $userIdNumber++;
            $userId = 'U' . str_pad($userIdNumber, 4, '0', STR_PAD_LEFT);
        }

        return $userId;
    }
}