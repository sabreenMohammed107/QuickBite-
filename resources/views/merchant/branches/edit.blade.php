@extends('merchant.layouts.app')
@section('title', 'Edit Branch')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('merchant.branches.index') }}" class="hover:text-indigo-600 transition-colors">Branches</a>
    <span>/</span>
    <span class="truncate font-medium text-slate-800">{{ $branch->label }}</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('merchant.branches.update', $branch) }}">
    @csrf @method('PUT')
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Edit Branch</h2>
        </div>
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div>
                <label for="label" class="{{ $label }}">Label <span class="text-red-500">*</span></label>
                <input id="label" name="label" type="text" value="{{ old('label', $branch->label) }}" class="{{ $input }}">
                @error('label') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="country_code" class="{{ $label }}">Country Code <span class="text-red-500">*</span></label>
                <input id="country_code" name="country_code" type="text" value="{{ old('country_code', $branch->country_code) }}" class="{{ $input }}" maxlength="2">
                @error('country_code') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="address_text" class="{{ $label }}">Address <span class="text-red-500">*</span></label>
                <input id="address_text" name="address_text" type="text" value="{{ old('address_text', $branch->address_text) }}" class="{{ $input }}">
                @error('address_text') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lat" class="{{ $label }}">Latitude <span class="text-red-500">*</span></label>
                <input id="lat" name="lat" type="number" step="any" value="{{ old('lat', $branch->lat) }}" class="{{ $input }}">
                @error('lat') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lng" class="{{ $label }}">Longitude <span class="text-red-500">*</span></label>
                <input id="lng" name="lng" type="number" step="any" value="{{ old('lng', $branch->lng) }}" class="{{ $input }}">
                @error('lng') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="opens_at" class="{{ $label }}">Opens At <span class="text-red-500">*</span></label>
                <input id="opens_at" name="opens_at" type="time" value="{{ old('opens_at', \Carbon\Carbon::parse($branch->opens_at)->format('H:i')) }}" class="{{ $input }}">
                @error('opens_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="closes_at" class="{{ $label }}">Closes At <span class="text-red-500">*</span></label>
                <input id="closes_at" name="closes_at" type="time" value="{{ old('closes_at', \Carbon\Carbon::parse($branch->closes_at)->format('H:i')) }}" class="{{ $input }}">
                @error('closes_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="delivery_radius" class="{{ $label }}">Delivery Radius (km) <span class="text-red-500">*</span></label>
                <input id="delivery_radius" name="delivery_radius" type="number" min="1" max="100" value="{{ old('delivery_radius', $branch->delivery_radius) }}" class="{{ $input }}">
                @error('delivery_radius') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-6 pt-2 sm:col-span-2">
                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $branch->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30">
                    <span class="text-sm text-slate-700">Active</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2.5">
                    <input type="checkbox" name="accept_orders" value="1" {{ old('accept_orders', $branch->accept_orders) ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30">
                    <span class="text-sm text-slate-700">Accept Orders</span>
                </label>
            </div>

        </div>
        <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <button type="button"
                    onclick="if(confirm('Delete branch {{ addslashes($branch->label) }}?')) document.getElementById('delete-form').submit()"
                    class="text-sm font-medium text-red-400 hover:text-red-600 transition-colors">Delete branch</button>
            <div class="flex items-center gap-3">
                <a href="{{ route('merchant.branches.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</a>
                <button type="submit"
                        class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">Save Changes</button>
            </div>
        </div>
    </div>
</form>

<form id="delete-form" method="POST" action="{{ route('merchant.branches.destroy', $branch) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection
