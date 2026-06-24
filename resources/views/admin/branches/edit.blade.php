@extends('layouts.dashboard')
@section('title', 'Edit Branch')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.branches.index') }}" class="transition-colors hover:text-orange-600">Branches</a>
    <span>/</span>
    <span class="font-medium text-slate-800">{{ $branch->label ?? $branch->address_text }}</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.branches.update', $branch) }}">
    @csrf @method('PUT')

    <div class="space-y-5">

        {{-- ─── Section: Identity ─── --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Branch Identity</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                <div class="sm:col-span-2">
                    <label for="restaurant_id" class="{{ $label }}">Restaurant <span class="text-red-500">*</span></label>
                    <select id="restaurant_id" name="restaurant_id" class="{{ $select }}">
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}" {{ old('restaurant_id', $branch->restaurant_id) == $r->id ? 'selected' : '' }}>
                                {{ $r->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="label" class="{{ $label }}">Branch Label</label>
                    <input id="label" name="label" type="text"
                           value="{{ old('label', $branch->label) }}"
                           class="{{ $input }}" placeholder="e.g. Downtown">
                    @error('label') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="country_code" class="{{ $label }}">Country Code <span class="text-red-500">*</span></label>
                    <input id="country_code" name="country_code" type="text"
                           value="{{ old('country_code', $branch->country_code) }}"
                           class="{{ $input }}" placeholder="US" maxlength="2">
                    @error('country_code') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="address_text" class="{{ $label }}">Full Address <span class="text-red-500">*</span></label>
                    <input id="address_text" name="address_text" type="text"
                           value="{{ old('address_text', $branch->address_text) }}"
                           class="{{ $input }}">
                    @error('address_text') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ─── Section: Location ─── --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">GPS Coordinates</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">

                <div>
                    <label for="lat" class="{{ $label }}">Latitude <span class="text-red-500">*</span></label>
                    <input id="lat" name="lat" type="number" step="any"
                           value="{{ old('lat', $branch->lat) }}"
                           class="{{ $input }}">
                    @error('lat') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lng" class="{{ $label }}">Longitude <span class="text-red-500">*</span></label>
                    <input id="lng" name="lng" type="number" step="any"
                           value="{{ old('lng', $branch->lng) }}"
                           class="{{ $input }}">
                    @error('lng') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="delivery_radius" class="{{ $label }}">Delivery Radius (km) <span class="text-red-500">*</span></label>
                    <input id="delivery_radius" name="delivery_radius" type="number" min="1" max="65535"
                           value="{{ old('delivery_radius', $branch->delivery_radius) }}"
                           class="{{ $input }}">
                    @error('delivery_radius') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- ─── Section: Hours & Settings ─── --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Hours &amp; Availability</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

                <div>
                    <label for="opens_at" class="{{ $label }}">Opening Time <span class="text-red-500">*</span></label>
                    <input id="opens_at" name="opens_at" type="time"
                           value="{{ old('opens_at', $branch->opens_at) }}"
                           class="{{ $input }}">
                    @error('opens_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="closes_at" class="{{ $label }}">Closing Time <span class="text-red-500">*</span></label>
                    <input id="closes_at" name="closes_at" type="time"
                           value="{{ old('closes_at', $branch->closes_at) }}"
                           class="{{ $input }}">
                    @error('closes_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2 space-y-4">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-orange-500"
                               {{ old('is_active', $branch->is_active) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-slate-700">Branch is Active</span>
                            <p class="text-xs text-slate-500">Inactive branches are hidden from customers.</p>
                        </div>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="accept_orders" value="0">
                        <input type="checkbox" name="accept_orders" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-orange-500"
                               {{ old('accept_orders', $branch->accept_orders) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-slate-700">Accept Orders</span>
                            <p class="text-xs text-slate-500">Toggle off to pause order intake without deactivating the branch.</p>
                        </div>
                    </label>
                </div>

            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
            <button type="button"
                    onclick="if(confirm('Delete this branch? This cannot be undone.')) document.getElementById('delete-branch-form').submit()"
                    class="text-sm font-medium text-red-400 transition-colors hover:text-red-600">
                Delete branch
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.branches.index') }}"
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

<form id="delete-branch-form" method="POST" action="{{ route('admin.branches.destroy', $branch) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@endsection
