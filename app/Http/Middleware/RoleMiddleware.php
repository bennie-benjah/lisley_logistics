<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check for each role
        foreach ($roles as $role) {
            // Method 1: Check role field directly
            if (isset($user->role) && $user->role === $role) {
                return $next($request);
            }
            
            // Method 2: Check is_admin for admin role
            if ($role === 'admin' && $user->is_admin) {
                return $next($request);
            }
            
            // Method 3: Using role checking method (Laravel Breeze/Jetstream)
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return $next($request);
            }
            
            // Method 4: Using Spatie Laravel Permission package
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return $next($request);
            }
        }

        // User doesn't have required role
        return response()->view('errors.403', [], 403);
    }
}