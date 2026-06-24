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
        $restaurants = Restaurant::orderBy('id')->get(['id', 'name']);

        return view('admin.products.create', compact('restaurants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id'  => ['required', 'exists:restaurants,id'],
            'name.en'        => ['required', 'string', 'max:255'],
            'name.ar'        => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:2000'],
            'description.ar' => ['nullable', 'string', 'max:2000'],
            'image_url'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['name']        = $request->input('name');
        $data['description'] = $request->filled('description.en') || $request->filled('description.ar')
            ? $request->input('description')
            : null;
        $data['image_url']   = $request->filled('image_url') ? $data['image_url'] : null;

        Product::create($data);

        $enName = (string) ($request->input('name.en') ?? '');

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$enName}\" created successfully.");
    }

    public function edit(Product $product)
    {
        $restaurants = Restaurant::orderBy('id')->get(['id', 'name']);

        return view('admin.products.edit', compact('product', 'restaurants'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'restaurant_id'  => ['required', 'exists:restaurants,id'],
            'name.en'        => ['required', 'string', 'max:255'],
            'name.ar'        => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string', 'max:2000'],
            'description.ar' => ['nullable', 'string', 'max:2000'],
            'image_url'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['name']        = $request->input('name');
        $data['description'] = $request->filled('description.en') || $request->filled('description.ar')
            ? $request->input('description')
            : null;
        $data['image_url']   = $request->filled('image_url') ? $data['image_url'] : null;

        $product->update($data);

        $displayName = $product->t('name');

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$displayName}\" updated successfully.");
    }

    public function destroy(Product $product)
    {
        $displayName = $product->t('name');
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$displayName}\" deleted.");
    }
}
