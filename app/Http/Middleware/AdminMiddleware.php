<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store intended URL for redirect after login
            if ($request->isMethod('get')) {
                session()->put('url.intended', $request->fullUrl());
            }
            
            return redirect('/#auth')->with('error', 'Please login as admin to access this page.');
        }

        $user = Auth::user();
        
        // Check if user has admin role using Spatie
        if (!$user->hasRole('admin')) {
            // If user is not admin, redirect to user dashboard
            return redirect()->route('dashboard')
                             ->with('error', 'You do not have admin privileges.');
        }

        return $next($request);
    }
}