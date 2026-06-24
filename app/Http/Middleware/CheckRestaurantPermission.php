<?php

namespace App\Http\Middleware;

use App\Domains\Auth\Enums\UserRole;
use App\Models\RestaurantMember;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRestaurantPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        // Global admin bypasses all permission checks
        if ($user->role === UserRole::Admin) {
            return $next($request);
        }

        $restaurantId = $request->route('restaurant')
            ?? $request->input('restaurant_id')
            ?? $request->session()->get('active_restaurant_id');

        if (! $restaurantId) {
            abort(403, 'Restaurant context required.');
        }

        $resolvedId = \is_object($restaurantId) ? $restaurantId->id : $restaurantId;

        $member = RestaurantMember::where('user_id', $user->id)
            ->where('restaurant_id', $resolvedId)
            ->where('status', 'active')
            ->first();

        if (! $member) {
            abort(403, 'You are not a member of this restaurant.');
        }

        if (! \in_array($permission, $member->permissions ?? [], true)) {
            abort(403, "Missing permission: {$permission}");
        }

        return $next($request);
    }
}
