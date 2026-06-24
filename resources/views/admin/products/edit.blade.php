@extends('layouts.dashboard')
@section('title', 'Edit Product')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.products.index') }}" class="transition-colors hover:text-orange-600">Products</a>
    <span>/</span>
    <span class="truncate font-medium text-slate-800">{{ $product->name }}</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf @method('PUT')

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Edit Product</h2>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div class="sm:col-span-2">
                <label for="restaurant_id" class="{{ $label }}">Restaurant <span class="text-red-500">*</span></label>
                <select id="restaurant_id" name="restaurant_id" class="{{ $select }}">
                    @foreach($restaurants as $r)
                        <option value="{{ $r->id }}" {{ old('restaurant_id', $product->restaurant_id) == $r->id ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
                @error('restaurant_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Product Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text"
                       value="{{ old('name', $product->name) }}"
                       class="{{ $input }}">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="{{ $label }}">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="{{ $input }} resize-none">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="image_url" class="{{ $label }}">Image URL</label>
                <input id="image_url" name="image_url" type="url"
                       value="{{ old('image_url', $product->image_url) }}"
                       class="{{ $input }}" placeholder="https://…">
                @error('image_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <button type="button"
                    onclick="if(confirm('Delete {{ addslashes($product->name) }}?')) document.getElementById('delete-product-form').submit()"
                    class="text-sm font-medium text-red-400 transition-colors hover:text-red-600">
                Delete product
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>

<form id="delete-product-form" method="POST" action="{{ route('admin.products.destroy', $product) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection
