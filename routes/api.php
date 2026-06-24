<?php

use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Restaurant\BranchController;
use App\Http\Controllers\Api\V1\Restaurant\NearbyBranchController;
use App\Http\Controllers\Api\V1\Restaurant\ProductAvailabilityController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', RegisterController::class);
        Route::post('login', LoginController::class);
    });

    Route::prefix('restaurants')->middleware('auth:sanctum')->group(function () {
        Route::get('branches/nearby', NearbyBranchController::class);
        Route::post('{restaurant}/branches', BranchController::class);
        Route::patch('branches/{productBranchDetail}/availability', ProductAvailabilityController::class);
    });

});
