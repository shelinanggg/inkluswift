<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $email = $request->email;
        $password = $request->password;

        // Cari user berdasarkan email
        $user = User::where('email', $email)->first();

        // Cek apakah user ada dan password benar
        if ($user && Hash::check($password, $user->password)) {
            // Cek status user
            if ($user->status === 'inactive') {
                return back()->withErrors([
                    'login' => 'Akun Anda sedang tidak aktif. Silakan hubungi administrator.'
                ])->withInput();
            }

            // Login berhasil - simpan data user ke session
            Session::put('user_id', $user->user_id);
            Session::put('user_name', $user->name);
            Session::put('user_email', $user->email);
            Session::put('user_role', $user->role);
            Session::put('user_status', $user->status);
            Session::put('is_logged_in', true);

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin')->with('success', 'Selamat datang, ' . $user->name);
                case 'staff':
                    return redirect()->route('admin')->with('success', 'Selamat datang, ' . $user->name);
                case 'customer':
                    return redirect()->route('home')->with('success', 'Selamat datang, ' . $user->name);
                default:
                    return redirect()->route('home')->with('success', 'Login berhasil');
            }
        }

        // Login gagal
        return back()->withErrors([
            'login' => 'Email atau password salah.'
        ])->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout()
    {
        // Hapus semua session
        Session::flush();
        
        return redirect()->route('landing')->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Get current logged in user data
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
     * Check if user is logged in
     */
    public function isLoggedIn()
    {
        return Session::has('is_logged_in') && Session::get('is_logged_in') === true;
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        return Session::get('user_role') === $role;
    }

    /**
     * Middleware untuk proteksi route yang memerlukan login
     */
    public function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
    }

    /**
     * Middleware untuk proteksi route berdasarkan role
     */
    public function requireRole($allowedRoles = [])
    {
        if (!$this->isLoggedIn()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userRole = Session::get('user_role');
        
        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }
}
