<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->isSuspended()) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been suspended. Please contact support for assistance.',
                ]);
            }
            
            if ($user->isDisabled()) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been disabled. Please contact support for assistance.',
                ]);
            }
        }

        return $next($request);
    }
}
