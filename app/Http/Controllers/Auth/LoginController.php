<?php

namespace App\Http\Controllers\Auth;

use App\Domains\Auth\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\RestaurantMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectForRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $user = Auth::user();
        $request->session()->regenerate();

        // For restaurant staff, bind their active restaurant to the session
        if ($user->role === UserRole::RestaurantOwner) {
            $member = RestaurantMember::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if (! $member) {
                Auth::logout();
                $request->session()->invalidate();

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your account is not assigned to any active restaurant. Please contact an administrator.']);
            }

            $request->session()->put('active_restaurant_id', $member->restaurant_id);
        }

        return $this->redirectForRole($user);
    }

    private function redirectForRole($user): RedirectResponse
    {
        return match ($user->role) {
            UserRole::Admin           => redirect()->intended(route('admin.restaurants.index')),
            UserRole::RestaurantOwner => redirect()->intended(route('merchant.dashboard')),
            default                   => redirect()->intended(route('home')),
        };
    }
}
