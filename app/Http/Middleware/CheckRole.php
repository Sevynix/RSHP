<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $request->session()->get('user_role');
        
        $roleMapping = [
            'admin' => 1,
            'dokter' => 2,
            'perawat' => 3,
            'resepsionis' => 4
        ];

        // Check if user role matches any of the allowed roles
        foreach ($roles as $role) {
            if (isset($roleMapping[$role]) && $userRole == $roleMapping[$role]) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
