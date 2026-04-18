<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isSuspended()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Votre compte a été suspendu. Contactez l\'administration.']);
        }

        return $next($request);
    }
}
