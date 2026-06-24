<?php

namespace App\Domains\Auth\Actions;

use App\Domains\Auth\Enums\UserRole;
use App\Domains\Auth\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class RegisterUserAction
{
    public function execute(string $name, string $email, string $password, UserRole $role = UserRole::Customer): User
    {
        return DB::connection('mysql_core')->transaction(function () use ($name, $email, $password, $role) {
            return User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'status' => UserStatus::Active,
            ]);
        });
    }
}
