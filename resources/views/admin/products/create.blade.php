@extends('layouts.dashboard')
@section('title', 'Add Product')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.products.index') }}" class="transition-colors hover:text-orange-600">Products</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.products.store') }}">
    @csrf

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Product Details</h2>
            <p class="mt-0.5 text-sm text-slate-500">This is the global catalog entry. Branch-specific pricing is set in <a href="{{ route('admin.product-details.index') }}" class="text-orange-500 hover:underline">Branch Catalog</a>.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div class="sm:col-span-2">
                <label for="restaurant_id" class="{{ $label }}">Restaurant <span class="text-red-500">*</span></label>
                <select id="restaurant_id" name="restaurant_id" class="{{ $select }}">
                    <option value="">Select restaurant…</option>
                    @foreach($restaurants as $r)
                        <option value="{{ $r->id }}" {{ old('restaurant_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                    @endforeach
                </select>
                @error('restaurant_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Product Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text"
                       value="{{ old('name') }}"
                       class="{{ $input }}" placeholder="e.g. Classic Cheeseburger">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="{{ $label }}">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="{{ $input }} resize-none"
                          placeholder="Short description of the product…">{{ old('description') }}</textarea>
                @error('description') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="image_url" class="{{ $label }}">Image URL <span class="text-slate-400 font-normal">(optional)</span></label>
                <input id="image_url" name="image_url" type="url"
                       value="{{ old('image_url') }}"
                       class="{{ $input }}" placeholder="https://…">
                @error('image_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                Create Product
            </button>
        </div>
    </div>
</form>

@endsection
