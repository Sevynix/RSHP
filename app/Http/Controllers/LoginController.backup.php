<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Cek user dengan role yang memiliki status aktif
        $user = DB::table('user as u')
            ->leftJoin('role_user as ru', function($query) {
                $query->on('ru.iduser', '=', 'u.iduser')
                      ->where('ru.status', '=', '1');
            })
            ->leftJoin('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'ru.idrole', 'r.nama_role')
            ->where('u.email', $request->email)
            ->whereNotNull('ru.idrole')
            ->first();

        if ($user) {
            // Cek password
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Password salah.'])
                    ->withInput();
            }

            // Login user ke session
            $request->session()->put([
                'user_id' => $user->iduser,
                'user_name' => $user->nama,
                'user_email' => $user->email,
                'user_role' => $user->idrole,
                'role_name' => $user->nama_role ?? 'User',
                'user_status' => 'active',
                'is_admin' => (strtolower($user->nama_role ?? '') === 'administrator')
            ]);

            $userRole = $user->idrole ?? null;

            // Switch berdasarkan role ID
            switch ($userRole) {
                case 1:
                    return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
                case 2:
                    return redirect()->route('dokter.dashboard')->with('success', 'Login berhasil!');
                case 3:
                    return redirect()->route('perawat.dashboard')->with('success', 'Login berhasil!');
                case 4:
                    return redirect()->route('resepsionis.dashboard')->with('success', 'Login berhasil!');
                default:
                    return redirect()->route('pemilik.dashboard')->with('success', 'Login berhasil!');
            }
        }

        // Cek apakah pemilik
        $pemilik = DB::table('user as u')
            ->join('pemilik as p', 'p.iduser', '=', 'u.iduser')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'p.idpemilik')
            ->where('u.email', $request->email)
            ->first();

        if ($pemilik) {
            // Cek password
            if (!Hash::check($request->password, $pemilik->password)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Password salah.'])
                    ->withInput();
            }

            // Login pemilik ke session
            $request->session()->put([
                'user_id' => $pemilik->iduser,
                'user_name' => $pemilik->nama,
                'user_email' => $pemilik->email,
                'user_role' => null,
                'role_name' => 'Pemilik',
                'idpemilik' => $pemilik->idpemilik,
                'user_status' => 'active',
                'is_admin' => false
            ]);

            return redirect()->route('pemilik.dashboard')->with('success', 'Login berhasil!');
        }

        // Cek user biasa (tanpa role dan bukan pemilik)
        $regularUser = DB::table('user')
            ->select('iduser', 'nama', 'email', 'password')
            ->where('email', $request->email)
            ->first();

        if ($regularUser) {
            // Cek password
            if (!Hash::check($request->password, $regularUser->password)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Password salah.'])
                    ->withInput();
            }

            // Login regular user ke session
            $request->session()->put([
                'user_id' => $regularUser->iduser,
                'user_name' => $regularUser->nama,
                'user_email' => $regularUser->email,
                'user_role' => null,
                'role_name' => 'User',
                'user_status' => 'active',
                'is_admin' => false
            ]);

            return redirect('/')->with('success', 'Login berhasil!');
        }

        // Jika email tidak ditemukan
        return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
