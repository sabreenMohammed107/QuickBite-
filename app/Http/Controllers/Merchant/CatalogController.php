<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBranchDetail;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CatalogController extends Controller
{
    private function restaurantId(): int
    {
        return session('active_restaurant_id');
    }

    private function ownsBranch(int $branchId): bool
    {
        return RestaurantBranch::where('id', $branchId)
            ->where('restaurant_id', $this->restaurantId())
            ->exists();
    }

    public function index()
    {
        $details = ProductBranchDetail::whereHas(
            'branch', fn ($q) => $q->where('restaurant_id', $this->restaurantId())
        )
            ->with(['branch', 'product'])
            ->latest()
            ->paginate(20);

        return view('merchant.catalog.index', compact('details'));
    }

    public function create()
    {
        $branches = RestaurantBranch::where('restaurant_id', $this->restaurantId())->get();
        $products = Product::where('restaurant_id', $this->restaurantId())->get();

        return view('merchant.catalog.create', compact('branches', 'products'));
    }

    public function store(Request $request)
    {
        $validBranchIds  = RestaurantBranch::where('restaurant_id', $this->restaurantId())->pluck('id');
        $validProductIds = Product::where('restaurant_id', $this->restaurantId())->pluck('id');

        $data = $request->validate([
            'branch_id'    => ['required', Rule::in($validBranchIds)],
            'product_id'   => ['required', Rule::in($validProductIds),
                Rule::unique('mysql_core.product_branch_details', 'product_id')
                    ->where('branch_id', $request->branch_id)],
            'price'        => ['required', 'numeric', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        ProductBranchDetail::create($data);

        return redirect()->route('merchant.catalog.index')
            ->with('success', 'Catalog entry created.');
    }

    public function edit(ProductBranchDetail $detail)
    {
        abort_unless($this->ownsBranch($detail->branch_id), 403);

        $branches = RestaurantBranch::where('restaurant_id', $this->restaurantId())->get();
        $products = Product::where('restaurant_id', $this->restaurantId())->get();

        return view('merchant.catalog.edit', compact('detail', 'branches', 'products'));
    }

    public function update(Request $request, ProductBranchDetail $detail)
    {
        abort_unless($this->ownsBranch($detail->branch_id), 403);

        $validBranchIds  = RestaurantBranch::where('restaurant_id', $this->restaurantId())->pluck('id');
        $validProductIds = Product::where('restaurant_id', $this->restaurantId())->pluck('id');

        $data = $request->validate([
            'branch_id'  => ['required', Rule::in($validBranchIds)],
            'product_id' => ['required', Rule::in($validProductIds),
                Rule::unique('mysql_core.product_branch_details', 'product_id')
                    ->where('branch_id', $request->branch_id)
                    ->ignore($detail->id)],
            'price'      => ['required', 'numeric', 'min:0'],
            'stock'      => ['required', 'integer', 'min:0'],
        ]);

        $data['is_available'] = $request->boolean('is_available');
        $detail->update($data);

        return redirect()->route('merchant.catalog.index')
            ->with('success', 'Catalog entry updated.');
    }

    public function destroy(ProductBranchDetail $detail)
    {
        abort_unless($this->ownsBranch($detail->branch_id), 403);

        $detail->delete();

        return redirect()->route('merchant.catalog.index')
            ->with('success', 'Catalog entry removed.');
    }
}
