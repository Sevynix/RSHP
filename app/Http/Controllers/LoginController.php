<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->email;
        $password = $request->password;

        $user = DB::table('user as u')
            ->leftJoin('role_user as ru', 'ru.iduser', '=', 'u.iduser')
            ->leftJoin('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'ru.idrole', 'r.nama_role')
            ->where('u.email', $email)
            ->where('ru.status', '1')
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            session([
                'logged_in' => true,
                'user_id' => $user->iduser,
                'email' => $user->email,
                'nama' => $user->nama,
                'role' => $user->nama_role,
                'is_admin' => ($user->nama_role === 'Administrator'),
                'user_type' => 'role',
                'login_time' => time(),
            ]);

            if ($user->nama_role === 'Administrator') {
                return redirect('/admin/dashboard')->with('success', 'Login berhasil!');
            } elseif (strtolower($user->nama_role) === 'resepsionis') {
                return redirect('/resepsionis/dashboard')->with('success', 'Login berhasil!');
            } elseif (strtolower($user->nama_role) === 'perawat') {
                return redirect('/perawat/dashboard')->with('success', 'Login berhasil!');
            } elseif (strtolower($user->nama_role) === 'dokter') {
                return redirect('/dokter/dashboard')->with('success', 'Login berhasil!');
            } else {
                return redirect('/')->with('success', 'Login berhasil!');
            }
        }

        $pemilik = DB::table('user as u')
            ->join('pemilik as p', 'p.iduser', '=', 'u.iduser')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'p.idpemilik')
            ->where('u.email', $email)
            ->first();

        if ($pemilik && Hash::check($password, $pemilik->password)) {
            session([
                'logged_in' => true,
                'user_id' => $pemilik->iduser,
                'email' => $pemilik->email,
                'nama' => $pemilik->nama,
                'role' => 'pemilik',
                'user_type' => 'pemilik',
                'idpemilik' => $pemilik->idpemilik,
                'login_time' => time(),
            ]);

            return redirect('/')->with('success', 'Login berhasil!');
        }

        $regularUser = DB::table('user')
            ->select('iduser', 'nama', 'email', 'password')
            ->where('email', $email)
            ->first();

        if ($regularUser && Hash::check($password, $regularUser->password)) {
            session([
                'logged_in' => true,
                'user_id' => $regularUser->iduser,
                'email' => $regularUser->email,
                'nama' => $regularUser->nama,
                'role' => 'user',
                'user_type' => 'user',
                'login_time' => time(),
            ]);

            return redirect('/')->with('success', 'Login berhasil!');
        }

        if ($regularUser) {
            return back()->with('error', 'Password salah!')->withInput();
        }

        return back()->with('error', 'Email tidak terdaftar!')->withInput();
    }

    public function logout(Request $request)
    {
        session()->flush();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
