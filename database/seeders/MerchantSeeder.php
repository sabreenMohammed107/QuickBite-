<?php

namespace Database\Seeders;

use App\Domains\Auth\Enums\UserRole;
use App\Domains\Auth\Enums\UserStatus;
use App\Domains\Restaurant\Enums\MemberRole;
use App\Models\Restaurant;
use App\Models\RestaurantMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        DB::connection('mysql_core')->transaction(function () {
            $restaurant = Restaurant::updateOrCreate(
                [],
                [
                    'name'            => ['en' => 'Demo Restaurant', 'ar' => 'مطعم تجريبي'],
                    'status'          => 'active',
                    'primary_country' => 'US',
                    'logo_url'        => null,
                ]
            );

            $user = User::updateOrCreate(
                ['email' => 'merchant@quickbite.com'],
                [
                    'name'     => 'Demo Merchant',
                    'password' => Hash::make('Merchant@1234'),
                    'role'     => UserRole::RestaurantOwner,
                    'status'   => UserStatus::Active,
                ]
            );

            RestaurantMember::updateOrCreate(
                ['user_id' => $user->id, 'restaurant_id' => $restaurant->id],
                [
                    'role'        => MemberRole::Owner,
                    'status'      => 'active',
                    'permissions' => [
                        'orders.view', 'orders.create', 'orders.update', 'orders.cancel',
                        'products.view', 'products.create', 'products.edit', 'products.delete',
                        'catalog.view', 'catalog.create', 'catalog.edit', 'catalog.delete',
                        'branches.view', 'branches.create', 'branches.edit', 'branches.delete',
                        'staff.view', 'staff.manage',
                        'reports.view',
                    ],
                ]
            );
        });

        $this->command->info('Demo Merchant  → merchant@quickbite.com / Merchant@1234');
        $this->command->info('Restaurant     → Demo Restaurant / مطعم تجريبي');
    }
}
