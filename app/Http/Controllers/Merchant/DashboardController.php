<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use App\Models\RestaurantMember;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $restaurantId = $request->session()->get('active_restaurant_id');

        $restaurant = Restaurant::findOrFail($restaurantId);

        $stats = [
            'branches'  => RestaurantBranch::where('restaurant_id', $restaurantId)->count(),
            'products'  => Product::where('restaurant_id', $restaurantId)->count(),
            'members'   => RestaurantMember::where('restaurant_id', $restaurantId)
                ->where('status', 'active')
                ->count(),
        ];

        return view('merchant.dashboard', compact('restaurant', 'stats'));
    }
}
