<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AccessCodeController extends Controller
{
    const MAX_ATTEMPTS = 3;
    const COOLDOWN = 30;
    public function verify(Request $request)
    {
        Validator::make($request->all(), [
            'type' => 'required|in:admin,staff',
            'code' => 'required|string|min:6'
        ])->validate();
        if (auth()->check()) {
            return response()->json(['error'=>'Already logged in'], 403);
        }
        $type = $request->type;
        $code = $request->code;
        $codes = [
            'admin' => env('ACCESS_CODE_ADMIN'),
            'staff' => env('ACCESS_CODE_STAFF')
        ];
        $ip = $request->ip();
        $attemptKey = "attempts_{$ip}_{$type}";
        $cooldownKey = "cooldown_{$ip}_{$type}";
        if (Cache::has($cooldownKey)) {
            $remaining = Cache::get($cooldownKey) - now()->timestamp;
            return response()->json([
                'status' => 'cooldown',
                'remaining' => max(0, $remaining)
            ]);
        }
        $attempts = Cache::get($attemptKey, 0);
        if (isset($codes[$type]) && hash_equals($codes[$type], $code)) {
            Cache::forget($attemptKey);
            return response()->json([
                'status' => 'success',
                'redirect' =>
                    $type === 'admin'
                        ? route('login.admin')
                        : route('login.staff')
            ]);
        }
        $attempts++;
        Cache::put($attemptKey, $attempts, now()->addSeconds(self::COOLDOWN));
        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::put($cooldownKey, now()->timestamp + self::COOLDOWN, self::COOLDOWN);
            Cache::forget($attemptKey);
            return response()->json([
                'status' => 'locked',
                'remaining' => self::COOLDOWN
            ]);
        }
        return response()->json([
            'status' => 'error',
            'attempts' => $attempts
        ]);
    }
}