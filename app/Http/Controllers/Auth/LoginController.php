<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request to the application.
     * Implements three-tier authentication: role users -> pemilik -> regular users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Attempt login with three-tier logic
        if ($this->attemptThreeTierLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $userRole = session('user_role');
        
        Log::info('sendLoginResponse - redirecting', ['user_role' => $userRole]);

        // Route based on role ID
        if ($userRole == 1 || $userRole === '1') {
            return redirect('admin/dashboard');
        } elseif ($userRole == 2 || $userRole === '2') {
            return redirect('dokter/dashboard');
        } elseif ($userRole == 3 || $userRole === '3') {
            return redirect('perawat/dashboard');
        } elseif ($userRole == 4 || $userRole === '4') {
            return redirect('resepsionis/dashboard');
        } elseif (session('idpemilik')) {
            return redirect('pemilik/dashboard');
        }
        
        return redirect('/');
    }

    /**
     * Attempt to log the user into the application with three-tier authentication.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptThreeTierLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        // Tier 1: Check user with active role (status = '1')
        $userWithRole = DB::table('user as u')
            ->leftJoin('role_user as ru', function($query) {
                $query->on('ru.iduser', '=', 'u.iduser')
                      ->where('ru.status', '=', '1');
            })
            ->leftJoin('role as r', 'r.idrole', '=', 'ru.idrole')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'ru.idrole', 'r.nama_role')
            ->where('u.email', $credentials['email'])
            ->whereNotNull('ru.idrole')
            ->first();

        if ($userWithRole && Auth::attempt($credentials, $request->filled('remember'))) {
            // Set custom session data for role-based user
            $request->session()->put([
                'user_id' => $userWithRole->iduser,
                'user_name' => $userWithRole->nama,
                'user_email' => $userWithRole->email,
                'user_role' => $userWithRole->idrole,
                'role_name' => $userWithRole->nama_role ?? 'User',
                'user_status' => 'active',
                'is_admin' => (strtolower($userWithRole->nama_role ?? '') === 'administrator')
            ]);

            // Force session save
            $request->session()->save();

            Log::info('User logged in with role', [
                'user_id' => $userWithRole->iduser,
                'user_role' => $userWithRole->idrole,
                'role_name' => $userWithRole->nama_role
            ]);

            return true;
        }

        // Logout if authenticated (to try next tier)
        Auth::logout();

        // Tier 2: Check if user is pemilik (pet owner)
        $pemilik = DB::table('user as u')
            ->join('pemilik as p', 'p.iduser', '=', 'u.iduser')
            ->select('u.iduser', 'u.nama', 'u.email', 'u.password', 'p.idpemilik')
            ->where('u.email', $credentials['email'])
            ->first();

        if ($pemilik && Auth::attempt($credentials, $request->filled('remember'))) {
            // Set custom session data for pemilik
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

            return true;
        }

        // Logout if authenticated (to try next tier)
        Auth::logout();

        // Tier 3: Check regular user (no role, not pemilik)
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Set custom session data for regular user
            $request->session()->put([
                'user_id' => $user->iduser,
                'user_name' => $user->nama,
                'user_email' => $user->email,
                'user_role' => null,
                'role_name' => 'User',
                'user_status' => 'active',
                'is_admin' => false
            ]);

            return true;
        }

        return false;
    }

    /**
     * Get the post login redirect path.
     * Dynamically determines redirect based on user role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $userRole = session('user_role');

        // Debug: Log the user role
        Log::info('Login redirect - user_role: ' . $userRole);

        // Route based on role ID
        switch ((int)$userRole) {
            case 1:
                return '/admin/dashboard';
            case 2:
                return '/dokter/dashboard';
            case 3:
                return '/perawat/dashboard';
            case 4:
                return '/resepsionis/dashboard';
            default:
                // Check if pemilik
                if (session('idpemilik')) {
                    return '/pemilik/dashboard';
                }
                return '/';
        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        $userRole = session('user_role');
        
        Log::info('Authenticated - redirecting based on role', [
            'user_role' => $userRole,
            'user_role_type' => gettype($userRole),
            'is_admin' => session('is_admin'),
            'idpemilik' => session('idpemilik')
        ]);

        // Route based on role ID - using strict comparison
        if ($userRole == 1 || $userRole === '1') {
            Log::info('Redirecting to admin dashboard');
            return redirect('admin/dashboard');
        } elseif ($userRole == 2 || $userRole === '2') {
            return redirect('dokter/dashboard');
        } elseif ($userRole == 3 || $userRole === '3') {
            return redirect('perawat/dashboard');
        } elseif ($userRole == 4 || $userRole === '4') {
            return redirect('resepsionis/dashboard');
        } elseif (session('idpemilik')) {
            return redirect('pemilik/dashboard');
        }
        
        return redirect('/');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
