<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Enums\UserRole;
use App\Models\RestaurantMember;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsMerchant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || Auth::user()->role !== UserRole::RestaurantOwner) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Restaurant staff access required.']);
        }

        // Re-hydrate session if the restaurant context was lost (e.g. session rotation)
        if (! $request->session()->has('active_restaurant_id')) {
            $member = RestaurantMember::where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if (! $member) {
                Auth::logout();
                $request->session()->invalidate();

                return redirect()->route('login')
                    ->withErrors(['email' => 'No active restaurant assignment found for your account.']);
            }

            $request->session()->put('active_restaurant_id', $member->restaurant_id);
        }

        return $next($request);
    }
}
