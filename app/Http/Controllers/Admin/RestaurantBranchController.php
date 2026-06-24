<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;

class RestaurantBranchController extends Controller
{
    public function index()
    {
        $branches = RestaurantBranch::with('restaurant')->latest()->paginate(20);

        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        $restaurants = Restaurant::orderBy('name')->get(['id', 'name']);

        return view('admin.branches.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id'   => ['required', 'exists:restaurants,id'],
            'country_code'    => ['required', 'string', 'size:2', 'alpha'],
            'address_text'    => ['required', 'string', 'max:500'],
            'label'           => ['nullable', 'string', 'max:100'],
            'lat'             => ['required', 'numeric', 'between:-90,90'],
            'lng'             => ['required', 'numeric', 'between:-180,180'],
            'opens_at'        => ['required', 'date_format:H:i'],
            'closes_at'       => ['required', 'date_format:H:i'],
            'delivery_radius' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        $data['country_code']   = strtoupper($data['country_code']);
        $data['is_active']      = $request->boolean('is_active');
        $data['accept_orders']  = $request->boolean('accept_orders');

        RestaurantBranch::create($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    }

    public function edit(RestaurantBranch $branch)
    {
        $restaurants = Restaurant::orderBy('name')->get(['id', 'name']);

        return view('admin.branches.edit', compact('branch', 'restaurants'));
    }

    public function update(Request $request, RestaurantBranch $branch)
    {
        $data = $request->validate([
            'restaurant_id'   => ['required', 'exists:restaurants,id'],
            'country_code'    => ['required', 'string', 'size:2', 'alpha'],
            'address_text'    => ['required', 'string', 'max:500'],
            'label'           => ['nullable', 'string', 'max:100'],
            'lat'             => ['required', 'numeric', 'between:-90,90'],
            'lng'             => ['required', 'numeric', 'between:-180,180'],
            'opens_at'        => ['required', 'date_format:H:i'],
            'closes_at'       => ['required', 'date_format:H:i'],
            'delivery_radius' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        $data['country_code']  = strtoupper($data['country_code']);
        $data['is_active']     = $request->boolean('is_active');
        $data['accept_orders'] = $request->boolean('accept_orders');

        $branch->update($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(RestaurantBranch $branch)
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted.');
    }
}
