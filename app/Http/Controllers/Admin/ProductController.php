<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('restaurant')->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $restaurants = Restaurant::orderBy('name')->get(['id', 'name']);

        return view('admin.products.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'image_url'     => ['nullable', 'url', 'max:500'],
        ]);

        $data['image_url']   = $request->filled('image_url') ? $data['image_url'] : null;
        $data['description'] = $request->filled('description') ? $data['description'] : null;

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$data['name']}\" created successfully.");
    }

    public function edit(Product $product)
    {
        $restaurants = Restaurant::orderBy('name')->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'restaurants'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'image_url'     => ['nullable', 'url', 'max:500'],
        ]);

        $data['image_url']   = $request->filled('image_url') ? $data['image_url'] : null;
        $data['description'] = $request->filled('description') ? $data['description'] : null;

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" updated successfully.");
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$name}\" deleted.");
    }
}
