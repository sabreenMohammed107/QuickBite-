<?php

namespace App\Http\Controllers\Api\V1\Restaurant;

use App\Domains\Restaurant\Actions\ToggleProductAvailabilityAction;
use App\Http\Controllers\Controller;
use App\Models\ProductBranchDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductAvailabilityController extends Controller
{
    public function __invoke(Request $request, ProductBranchDetail $productBranchDetail, ToggleProductAvailabilityAction $action): JsonResponse
    {
        $detail = $action->execute($productBranchDetail);

        return response()->json(['data' => $detail]);
    }
}
