<?php

namespace App\Domains\Auth\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class LoginUserAction
{
    public function execute(string $email, string $password, bool $remember = false): ?User
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            return null;
        }

        /** @var User */
        return Auth::user();
    }
}
