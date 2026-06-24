<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    private function restaurantId(): int
    {
        return session('active_restaurant_id');
    }

    public function index()
    {
        $branches = RestaurantBranch::where('restaurant_id', $this->restaurantId())
            ->latest()
            ->paginate(20);

        return view('merchant.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('merchant.branches.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'country_code'    => ['required', 'string', 'size:2', 'alpha'],
            'address_text.en' => ['required', 'string', 'max:500'],
            'address_text.ar' => ['nullable', 'string', 'max:500'],
            'label.en'        => ['required', 'string', 'max:255'],
            'label.ar'        => ['nullable', 'string', 'max:255'],
            'lat'             => ['required', 'numeric', 'between:-90,90'],
            'lng'             => ['required', 'numeric', 'between:-180,180'],
            'opens_at'        => ['required', 'date_format:H:i'],
            'closes_at'       => ['required', 'date_format:H:i'],
            'delivery_radius' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $data['country_code']  = strtoupper($data['country_code']);
        $data['is_active']     = $request->boolean('is_active');
        $data['accept_orders'] = $request->boolean('accept_orders');
        $data['address_text']  = $request->input('address_text');
        $data['label']         = $request->input('label');
        $data['restaurant_id'] = $this->restaurantId();

        RestaurantBranch::create($data);

        $displayLabel = (string) ($request->input('label.en') ?? '');

        return redirect()->route('merchant.branches.index')
            ->with('success', "Branch \"{$displayLabel}\" created.");
    }

    public function edit(RestaurantBranch $branch)
    {
        abort_unless($branch->restaurant_id === $this->restaurantId(), 403);

        return view('merchant.branches.edit', compact('branch'));
    }

    public function update(Request $request, RestaurantBranch $branch)
    {
        abort_unless($branch->restaurant_id === $this->restaurantId(), 403);

        $data = $request->validate([
            'country_code'    => ['required', 'string', 'size:2', 'alpha'],
            'address_text.en' => ['required', 'string', 'max:500'],
            'address_text.ar' => ['nullable', 'string', 'max:500'],
            'label.en'        => ['required', 'string', 'max:255'],
            'label.ar'        => ['nullable', 'string', 'max:255'],
            'lat'             => ['required', 'numeric', 'between:-90,90'],
            'lng'             => ['required', 'numeric', 'between:-180,180'],
            'opens_at'        => ['required', 'date_format:H:i'],
            'closes_at'       => ['required', 'date_format:H:i'],
            'delivery_radius' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $data['country_code']  = strtoupper($data['country_code']);
        $data['is_active']     = $request->boolean('is_active');
        $data['accept_orders'] = $request->boolean('accept_orders');
        $data['address_text']  = $request->input('address_text');
        $data['label']         = $request->input('label');

        $branch->update($data);

        $displayLabel = $branch->t('label');

        return redirect()->route('merchant.branches.index')
            ->with('success', "Branch \"{$displayLabel}\" updated.");
    }

    public function destroy(RestaurantBranch $branch)
    {
        abort_unless($branch->restaurant_id === $this->restaurantId(), 403);

        $displayLabel = $branch->t('label');
        $branch->delete();

        return redirect()->route('merchant.branches.index')
            ->with('success', "Branch \"{$displayLabel}\" deleted.");
    }
}
