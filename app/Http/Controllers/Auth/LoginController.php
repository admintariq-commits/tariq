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
        try {
            $credentials = $request->validated();
            
            logger()->info('Login attempt', ['email' => $credentials['email']]);
            
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                logger()->info('User authenticated', ['user_id' => $user->id, 'role_id' => $user->role_id]);
                
                // Update security info
                try {
                    $user->update([
                        'last_login_at' => now(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->header('user-agent'),
                        'bot_score' => $request->input('bot_score', 0),
                        'vpn_detected' => $request->input('vpn_detected', false),
                    ]);
                    logger()->info('User security info updated', ['user_id' => $user->id]);
                } catch (\Throwable $e) {
                    logger()->error('Failed to update security info: ' . $e->getMessage(), ['user_id' => $user->id]);
                }
                
                // Track suspicious activity
                if ($request->input('is_suspicious', false)) {
                    try {
                        $user->update([
                            'is_suspicious' => true,
                            'last_suspicious_activity_at' => now(),
                            'security_flags' => $request->input('security_flags', []),
                        ]);
                    } catch (\Throwable $e) {
                        logger()->warning('Failed to track suspicious activity: ' . $e->getMessage());
                    }
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
                
                // Redirect based on role
                try {
                    if ($user->role && $user->role->name === 'admin') {
                        logger()->info('Redirecting to admin dashboard', ['user_id' => $user->id]);
                        return redirect('/admin/dashboard');
                    }
                    if ($user->role && $user->role->name === 'ministry') {
                        logger()->info('Redirecting to ministry dashboard', ['user_id' => $user->id]);
                        return redirect('/ministry/dashboard');
                    }
                    if ($user->role && $user->role->name === 'graduate') {
                        logger()->info('Redirecting to graduate profile', ['user_id' => $user->id]);
                        return redirect('/graduate/profile');
                    }
                    logger()->info('No role match, redirecting to home', ['user_id' => $user->id, 'role' => $user->role_id]);
                    return redirect()->route('home');
                } catch (\Throwable $e) {
                    logger()->error('Failed during redirect: ' . $e->getMessage(), ['user_id' => $user->id]);
                    return redirect()->route('home');
                }
            }
            
            logger()->info('Authentication failed for email', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Invalid credentials',
            ]);
        } catch (\Throwable $e) {
            logger()->error('Login exception: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->input('email'),
            ]);
            return back()->withErrors([
                'login' => 'Unable to process login at this time. Please try again later.',
            ]);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}