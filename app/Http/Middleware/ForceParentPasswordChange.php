<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForceParentPasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role === 'orangtua') {
            $isOnChangePasswordRoute = $request->routeIs('parent.password.*');

            if (!$isOnChangePasswordRoute && empty($user->password_changed_at)) {
                return redirect()->route('parent.password.edit');
            }
        }

        return $next($request);
    }
}
