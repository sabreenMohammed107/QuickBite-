<?php

namespace App\Domains\Restaurant\Actions;

use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use Illuminate\Support\Facades\DB;

final class CreateBranchAction
{
    public function execute(Restaurant $restaurant, array $data): RestaurantBranch
    {
        return DB::connection('mysql_core')->transaction(function () use ($restaurant, $data) {
            /** @var RestaurantBranch */
            return $restaurant->branches()->create($data);
        });
    }
}
