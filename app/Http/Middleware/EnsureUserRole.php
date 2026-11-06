<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan masuk terlebih dahulu untuk mengakses halaman ini.');
        }

        $user = Auth::user();

        // Check if user has any of the required roles
        $allowed = false;
        foreach ($roles as $role) {
            $role = strtolower(trim($role));
            if ($role === 'user' && $user->isUser()) {
                $allowed = true;
                break;
            }
            if ($role === 'fasil' && $user->isFasil()) {
                $allowed = true;
                break;
            }
            if ($role === 'admin' && $user->isAdmin()) {
                $allowed = true;
                break;
            }
            // Allow admin to access everything
            if ($user->isAdmin()) {
                $allowed = true;
                break;
            }
        }

        if (! $allowed) {
            return redirect($user->home_route)->with('info', 'Anda tidak memiliki hak akses ke halaman tersebut. Dialihkan ke dashboard Anda.');
        }

        return $next($request);
    }
}
