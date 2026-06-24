<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::latest()->paginate(20);

        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'status'          => ['required', Rule::in(['active', 'inactive', 'pending_review'])],
            'logo_url'        => ['nullable', 'url', 'max:500'],
            'primary_country' => ['required', 'string', 'size:2', 'alpha'],
        ]);

        $data['primary_country'] = strtoupper($data['primary_country']);
        $data['logo_url']        = $request->filled('logo_url') ? $data['logo_url'] : null;

        Restaurant::create($data);

        return redirect()->route('admin.restaurants.index')
            ->with('success', "Restaurant \"{$data['name']}\" created successfully.");
    }

    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'status'          => ['required', Rule::in(['active', 'inactive', 'pending_review'])],
            'logo_url'        => ['nullable', 'url', 'max:500'],
            'primary_country' => ['required', 'string', 'size:2', 'alpha'],
        ]);

        $data['primary_country'] = strtoupper($data['primary_country']);
        $data['logo_url']        = $request->filled('logo_url') ? $data['logo_url'] : null;

        $restaurant->update($data);

        return redirect()->route('admin.restaurants.index')
            ->with('success', "Restaurant \"{$restaurant->name}\" updated successfully.");
    }

    public function destroy(Restaurant $restaurant)
    {
        $name = $restaurant->name;
        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')
            ->with('success', "Restaurant \"{$name}\" deleted.");
    }
}
