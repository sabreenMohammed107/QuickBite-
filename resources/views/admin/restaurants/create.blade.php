@extends('layouts.dashboard')
@section('title', 'Add Restaurant')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.restaurants.index') }}" class="hover:text-orange-600 transition-colors">Restaurants</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.restaurants.store') }}">
    @csrf

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Restaurant Information</h2>
            <p class="mt-0.5 text-sm text-slate-500">Fill in the brand-level details. Branches are managed separately.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            {{-- Name --}}
            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Restaurant Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text"
                       value="{{ old('name') }}"
                       class="{{ $input }}" placeholder="e.g. Burger Palace">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="{{ $label }}">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="{{ $select }}">
                    <option value="">Select status…</option>
                    <option value="pending_review" {{ old('status', 'pending_review') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="active"         {{ old('status') === 'active'         ? 'selected' : '' }}>Active</option>
                    <option value="inactive"       {{ old('status') === 'inactive'       ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Primary Country --}}
            <div>
                <label for="primary_country" class="{{ $label }}">Primary Country Code <span class="text-red-500">*</span></label>
                <input id="primary_country" name="primary_country" type="text"
                       value="{{ old('primary_country') }}"
                       class="{{ $input }}" placeholder="US" maxlength="2">
                <p class="mt-1 text-xs text-slate-400">2-letter ISO 3166-1 alpha-2 code (e.g. US, GB, AE).</p>
                @error('primary_country') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Logo URL --}}
            <div class="sm:col-span-2">
                <label for="logo_url" class="{{ $label }}">Logo URL <span class="text-slate-400 font-normal">(optional)</span></label>
                <input id="logo_url" name="logo_url" type="url"
                       value="{{ old('logo_url') }}"
                       class="{{ $input }}" placeholder="https://…">
                @error('logo_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Form footer --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <a href="{{ route('admin.restaurants.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                Create Restaurant
            </button>
        </div>
    </div>
</form>

@endsection
