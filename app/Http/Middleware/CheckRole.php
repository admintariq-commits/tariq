<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        
        $user = Auth::user();
        
        if (!$user->role || !in_array($user->role->name, $roles)) {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}