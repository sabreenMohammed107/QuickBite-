<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function restaurantId(): int
    {
        return session('active_restaurant_id');
    }

    public function index()
    {
        $products = Product::where('restaurant_id', $this->restaurantId())
            ->latest()
            ->paginate(20);

        return view('merchant.products.index', compact('products'));
    }

    public function create()
    {
        return view('merchant.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name.en'        => ['required', 'string', 'max:255'],
            'name.ar'        => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'image_url'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['name']          = $request->input('name');
        $data['description']   = $request->filled('description.en') || $request->filled('description.ar')
            ? $request->input('description')
            : null;
        $data['image_url']     = $request->filled('image_url') ? $data['image_url'] : null;
        $data['restaurant_id'] = $this->restaurantId();

        Product::create($data);

        $displayName = (string) ($request->input('name.en') ?? '');

        return redirect()->route('merchant.products.index')
            ->with('success', "Product \"{$displayName}\" created.");
    }

    public function edit(Product $product)
    {
        abort_unless($product->restaurant_id === $this->restaurantId(), 403);

        return view('merchant.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->restaurant_id === $this->restaurantId(), 403);

        $data = $request->validate([
            'name.en'        => ['required', 'string', 'max:255'],
            'name.ar'        => ['nullable', 'string', 'max:255'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'image_url'      => ['nullable', 'url', 'max:500'],
        ]);

        $data['name']        = $request->input('name');
        $data['description'] = $request->filled('description.en') || $request->filled('description.ar')
            ? $request->input('description')
            : null;
        $data['image_url']   = $request->filled('image_url') ? $data['image_url'] : null;

        $product->update($data);

        $displayName = $product->t('name');

        return redirect()->route('merchant.products.index')
            ->with('success', "Product \"{$displayName}\" updated.");
    }

    public function destroy(Product $product)
    {
        abort_unless($product->restaurant_id === $this->restaurantId(), 403);

        $displayName = $product->t('name');
        $product->delete();

        return redirect()->route('merchant.products.index')
            ->with('success', "Product \"{$displayName}\" deleted.");
    }
}
