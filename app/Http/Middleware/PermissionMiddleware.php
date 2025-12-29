<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check for admin override
        if ($user->is_admin) {
            return $next($request);
        }

        // Check specific permissions
        foreach ($permissions as $permission) {
            // Method 1: Direct permission check
            if (isset($user->permissions) && in_array($permission, $user->permissions)) {
                return $next($request);
            }
            
            // Method 2: Using role-based permissions
            if (method_exists($user, 'hasPermission') && $user->hasPermission($permission)) {
                return $next($request);
            }
            
            // Method 3: Using Spatie Laravel Permission package
            if (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo($permission)) {
                return $next($request);
            }
        }

        // User doesn't have required permissions
        return response()->view('errors.403', [], 403);
    }
}