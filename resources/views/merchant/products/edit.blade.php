@extends('merchant.layouts.app')
@section('title', 'Edit Product')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('merchant.products.index') }}" class="hover:text-indigo-600 transition-colors">Products</a>
    <span>/</span>
    <span class="truncate font-medium text-slate-800">{{ $product->name }}</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('merchant.products.update', $product) }}">
    @csrf @method('PUT')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Edit Product</h2>
        </div>
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" class="{{ $input }}">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="{{ $label }}">Description</label>
                <textarea id="description" name="description" rows="3"
                          class="{{ $input }}">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="image_url" class="{{ $label }}">Image URL <span class="text-slate-400 font-normal">(optional)</span></label>
                <input id="image_url" name="image_url" type="url" value="{{ old('image_url', $product->image_url) }}" class="{{ $input }}" placeholder="https://…">
                @error('image_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>
        <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <button type="button"
                    onclick="if(confirm('Delete {{ addslashes($product->name) }}?')) document.getElementById('delete-form').submit()"
                    class="text-sm font-medium text-red-400 hover:text-red-600 transition-colors">Delete product</button>
            <div class="flex items-center gap-3">
                <a href="{{ route('merchant.products.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</a>
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">Save Changes</button>
            </div>
        </div>
    </div>
</form>

<form id="delete-form" method="POST" action="{{ route('merchant.products.destroy', $product) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection
