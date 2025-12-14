<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has a parent record
        $user = Auth::user();
        $isParent = \App\Models\Orangtua::where('id_user', $user->id)->exists();

        if (!$isParent) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Parent access only.');
        }

        return $next($request);
    }
}
