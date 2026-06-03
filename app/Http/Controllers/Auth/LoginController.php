<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginNotification;
class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Update security info
            $user->update([
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('user-agent'),
                'bot_score' => $request->input('bot_score', 0),
                'vpn_detected' => $request->input('vpn_detected', false),
            ]);
            
            // Track suspicious activity
            if ($request->input('is_suspicious', false)) {
                $user->update([
                    'is_suspicious' => true,
                    'last_suspicious_activity_at' => now(),
                    'security_flags' => $request->input('security_flags', []),
                ]);
            }
            
            // Notify configured admin email when an admin user signs in
            try {
                $adminEmail = env('ADMIN_EMAIL');
                if ($user->role && $user->role->name === 'admin' && $adminEmail) {
                    // avoid emailing the same address as the logged-in admin
                    if (strtolower($adminEmail) !== strtolower($user->email)) {
                        Mail::to($adminEmail)->send(new AdminLoginNotification($user, request()->ip()));
                    }
                }
            } catch (\Throwable $e) {
                // Log silently - do not block login on mail errors
                logger()->warning('Failed to send admin login notification: ' . $e->getMessage());
            }
            if ($user->role && $user->role->name === 'admin') {
                return redirect('/admin/dashboard');
            }
            if ($user->role && $user->role->name === 'ministry') {
                return redirect('/ministry/dashboard');
            }
            if ($user->role && $user->role->name === 'graduate') {
                return redirect('/graduate/profile');
            }
            return redirect()->route('home');
        }
        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}