<?php

namespace App\Domains\Restaurant\Actions;

use App\Models\ProductBranchDetail;
use Illuminate\Support\Facades\DB;

final class ToggleProductAvailabilityAction
{
    public function execute(ProductBranchDetail $detail): ProductBranchDetail
    {
        return DB::connection('mysql_core')->transaction(function () use ($detail) {
            $detail->update(['is_available' => ! $detail->is_available]);

            return $detail->refresh();
        });
    }
}
