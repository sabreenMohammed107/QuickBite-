<?php

namespace App\Http\Controllers\Api\V1\Restaurant;

use App\Domains\Restaurant\Actions\CreateBranchAction;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __invoke(Request $request, Restaurant $restaurant, CreateBranchAction $action): JsonResponse
    {
        $data = $request->validate([
            'country_code' => ['required', 'string', 'size:2'],
            'address_text' => ['required', 'string', 'max:500'],
            'label' => ['sometimes', 'nullable', 'string', 'max:100'],
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'opens_at' => ['required', 'date_format:H:i'],
            'closes_at' => ['required', 'date_format:H:i', 'after:opens_at'],
            'delivery_radius' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        $branch = $action->execute($restaurant, $data);

        return response()->json(['data' => $branch], 201);
    }
}
