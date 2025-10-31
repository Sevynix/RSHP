<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isAdministrator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() ){
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $userole = session ('user_role');

        if ($userole === 1) {
            return $next($request);
        } else{
            return back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses.');
        }
    }
}
