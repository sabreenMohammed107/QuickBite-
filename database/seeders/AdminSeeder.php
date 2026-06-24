<?php

namespace Database\Seeders;

use App\Domains\Auth\Enums\UserRole;
use App\Domains\Auth\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@quickbite.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('Admin@1234'),
                'role'     => UserRole::Admin,
                'status'   => UserStatus::Active,
            ]
        );

        $this->command->info('Super Admin created → admin@quickbite.com / Admin@1234');
    }
}
