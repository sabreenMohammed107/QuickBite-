@extends('merchant.layouts.app')
@section('title', 'Edit Catalog Entry')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('merchant.catalog.index') }}" class="hover:text-indigo-600 transition-colors">Branch Catalog</a>
    <span>/</span>
    <span class="font-medium text-slate-800">{{ $detail->product->name ?? 'Entry' }}</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('merchant.catalog.update', $detail) }}">
    @csrf @method('PUT')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Edit Catalog Entry</h2>
        </div>
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div>
                <label for="branch_id" class="{{ $label }}">Branch <span class="text-red-500">*</span></label>
                <select id="branch_id" name="branch_id" class="{{ $select }}">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $detail->branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->label }}
                        </option>
                    @endforeach
                </select>
                @error('branch_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="product_id" class="{{ $label }}">Product <span class="text-red-500">*</span></label>
                <select id="product_id" name="product_id" class="{{ $select }}">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $detail->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price" class="{{ $label }}">Price <span class="text-red-500">*</span></label>
                <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $detail->price) }}" class="{{ $input }}">
                @error('price') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stock" class="{{ $label }}">Stock <span class="text-red-500">*</span></label>
                <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $detail->stock) }}" class="{{ $input }}">
                @error('stock') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $detail->is_available) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30">
                    <span class="text-sm text-slate-700">Available for ordering</span>
                </label>
            </div>

        </div>
        <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <button type="button"
                    onclick="if(confirm('Remove this catalog entry?')) document.getElementById('delete-form').submit()"
                    class="text-sm font-medium text-red-400 hover:text-red-600 transition-colors">Remove entry</button>
            <div class="flex items-center gap-3">
                <a href="{{ route('merchant.catalog.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</a>
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">Save Changes</button>
            </div>
        </div>
    </div>
</form>

<form id="delete-form" method="POST" action="{{ route('merchant.catalog.destroy', $detail) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection
