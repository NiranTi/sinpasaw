<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return redirect('/login');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK ROLE
        |--------------------------------------------------------------------------
        */

        if (Auth::user()->role !== $role) {

            /*
            |--------------------------------------------------------------------------
            | JIKA BUKAN ROLE YANG SESUAI
            |--------------------------------------------------------------------------
            */

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
