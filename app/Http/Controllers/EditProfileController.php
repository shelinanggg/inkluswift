<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EditProfileController extends Controller
{
    /**
     * Menampilkan form edit profile
     */
    public function showEditForm()
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data user dari database berdasarkan session
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan');
        }

        return view('edit-profile', compact('user'));
    }

    /**
     * Update profile user
     */
    public function updateProfile(Request $request)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan');
        }

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan pengguna lain',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',
            'address.max' => 'Alamat maksimal 500 karakter',
            'profile_picture.image' => 'File harus berupa gambar',
            'profile_picture.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'profile_picture.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle upload profile picture
        $profilePicturePath = $user->profile_picture; // Keep existing if no new upload
        
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Upload foto baru
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Update data user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'profile_picture' => $profilePicturePath,
        ]);

        // Update session dengan data terbaru
        Session::put('user_name', $user->name);
        Session::put('user_email', $user->email);

        return redirect()->back()->with('success', 'Profile berhasil diupdate');
    }

    /**
     * Menampilkan form ganti password
     */
    public function showChangePasswordForm()
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil data user dari database berdasarkan session - PERBAIKAN INI YANG KURANG
        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan');
        }

        // Pass variabel $user ke view - PERBAIKAN INI YANG KURANG
        return view('change-password', compact('user'));
    }

    /**
     * Update password user
     */
    public function updatePassword(Request $request)
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan');
        }

        // Validasi input dengan aturan password yang lebih ketat
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
            'new_password.regex' => 'Password baru harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus',
        ]);

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama tidak benar'
            ]);
        }

        // Cek apakah password baru berbeda dari password lama
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors([
                'new_password' => 'Password baru harus berbeda dari password lama'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }

    /**
     * Hapus foto profile
     */
    public function removeProfilePicture()
    {
        // Cek apakah user sudah login
        if (!Session::has('is_logged_in')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userId = Session::get('user_id');
        $user = User::where('user_id', $userId)->first();

        if (!$user) {
            Session::flush();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan');
        }

        // Hapus foto dari storage jika ada
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Update database
        $user->update([
            'profile_picture' => null
        ]);

        return redirect()->back()->with('success', 'Foto profile berhasil dihapus');
    }

    /**
     * Get current user data (helper method)
     */
    public function getCurrentUser()
    {
        if (!Session::has('is_logged_in')) {
            return null;
        }

        $userId = Session::get('user_id');
        return User::where('user_id', $userId)->first();
    }

    /**
     * Cek apakah user sudah login (helper method)
     */
    public function isLoggedIn()
    {
        return Session::has('is_logged_in') && Session::get('is_logged_in') === true;
    }
}