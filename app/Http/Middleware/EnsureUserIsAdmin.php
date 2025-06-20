<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->hasRole('admin')) {
            abort(403, 'Hanya admin yang bisa mengakses dashboard.');
        }

        return $next($request);
    }
}
