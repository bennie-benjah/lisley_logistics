<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            // Store intended URL for redirect after login
            if ($request->isMethod('get')) {
                session()->put('url.intended', $request->fullUrl());
            }
            
            return redirect('/#auth')->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();
        
        // Check if user has ANY role
        if (!$this->hasAnyRole($user)) {
            // Assign default 'user' role if no role exists
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('user');
            }
            
            return redirect()->route('dashboard')
                             ->with('info', 'Default user role assigned. You can now access all features.');
        }
        
        // If user is admin trying to access user-only area, redirect to admin dashboard
        if ($this->isAdmin($user)) {
            return redirect()->route('admin.dashboard')
                             ->with('info', 'Please use admin dashboard for admin features.');
        }
        
        return $next($request);
    }
    
    /**
     * Check if user has any role
     */
    private function hasAnyRole($user): bool
    {
        // Using Spatie roles
        if (method_exists($user, 'roles')) {
            return $user->roles()->exists();
        }
        
        // Fallback: check is_admin field
        return isset($user->is_admin);
    }
    
    /**
     * Check if user is admin
     */
    private function isAdmin($user): bool
    {
        // Using Spatie roles
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('admin');
        }
        
        // Fallback: check is_admin field
        return isset($user->is_admin) && $user->is_admin;
    }
}