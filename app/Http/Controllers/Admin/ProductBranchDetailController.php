<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductBranchDetail;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductBranchDetailController extends Controller
{
    public function index()
    {
        $details = ProductBranchDetail::with(['branch.restaurant', 'product'])
            ->latest()
            ->paginate(20);

        return view('admin.product-details.index', compact('details'));
    }

    public function create()
    {
        $branches = RestaurantBranch::with('restaurant')->orderBy('id')->get();
        $products = Product::with('restaurant')->orderBy('name')->get();

        return view('admin.product-details.create', compact('branches', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id'    => ['required', 'exists:restaurant_branches,id'],
            'product_id'   => [
                'required',
                'exists:products,id',
                Rule::unique('product_branch_details', 'product_id')
                    ->where('branch_id', $request->input('branch_id')),
            ],
            'price'  => ['required', 'numeric', 'min:0'],
            'stock'  => ['required', 'integer', 'min:0'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        ProductBranchDetail::create($data);

        return redirect()->route('admin.product-details.index')
            ->with('success', 'Branch catalog entry created successfully.');
    }

    public function edit(ProductBranchDetail $detail)
    {
        $branches = RestaurantBranch::with('restaurant')->orderBy('id')->get();
        $products = Product::with('restaurant')->orderBy('name')->get();

        return view('admin.product-details.edit', compact('detail', 'branches', 'products'));
    }

    public function update(Request $request, ProductBranchDetail $detail)
    {
        $data = $request->validate([
            'branch_id'  => ['required', 'exists:restaurant_branches,id'],
            'product_id' => [
                'required',
                'exists:products,id',
                Rule::unique('product_branch_details', 'product_id')
                    ->where('branch_id', $request->input('branch_id'))
                    ->ignore($detail->id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $data['is_available'] = $request->boolean('is_available');

        $detail->update($data);

        return redirect()->route('admin.product-details.index')
            ->with('success', 'Branch catalog entry updated.');
    }

    public function destroy(ProductBranchDetail $detail)
    {
        $detail->delete();

        return redirect()->route('admin.product-details.index')
            ->with('success', 'Branch catalog entry deleted.');
    }
}
