<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->role !== UserRole::Admin) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Admin access required.']);
        }

        return $next($request);
    }
}
