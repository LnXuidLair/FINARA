<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Check if user is orangtua and hasn't changed password
        if ($user && $user->role === 'orangtua' && empty($user->password_changed_at)) {
            // Allow access to password change page and logout
            $allowedRoutes = [
                'parent.password.edit',
                'parent.password.update',
                'logout'
            ];
            
            if (!$request->routeIs($allowedRoutes)) {
                return redirect()->route('parent.password.edit')
                    ->with('warning', 'Anda wajib mengganti password pada login pertama kali.');
            }
        }
        
        return $next($request);
    }
}
