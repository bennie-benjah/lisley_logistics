<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Redirect based on user type
            if (isset($user->is_admin) && $user->is_admin === 1) {
                return redirect()->route('admin.dashboard');
            }
            
            // Normal users go to their dashboard
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}