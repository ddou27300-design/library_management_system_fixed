<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // ឆែកថា តើ User បាន Login ឬនៅ? និងមាន Role ត្រូវតាមការកំណត់ឬទេ?
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}