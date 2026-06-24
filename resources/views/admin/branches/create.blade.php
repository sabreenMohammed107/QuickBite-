@extends('layouts.dashboard')
@section('title', 'Add Branch')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.branches.index') }}" class="transition-colors hover:text-orange-600">Branches</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.branches.store') }}">
    @csrf

    <div class="space-y-5">

        {{-- Restaurant + Country (non-translatable) --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Branch Identity</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="restaurant_id" class="{{ $label }}">Restaurant <span class="text-red-500">*</span></label>
                    <select id="restaurant_id" name="restaurant_id" class="{{ $select }}">
                        <option value="">Select restaurant…</option>
                        @foreach($restaurants as $r)
                            <option value="{{ $r->id }}" {{ old('restaurant_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->t('name') }}
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="country_code" class="{{ $label }}">Country Code <span class="text-red-500">*</span></label>
                    <input id="country_code" name="country_code" type="text"
                           value="{{ old('country_code') }}"
                           class="{{ $input }}" placeholder="US" maxlength="2">
                    <p class="mt-1 text-xs text-slate-400">2-letter ISO code.</p>
                    @error('country_code') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Translatable: label + address --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Label &amp; Address</h2>
            </div>

            <div class="border-b border-slate-100 bg-slate-50/40 px-6 py-3">
                <div class="flex w-fit rounded-xl border border-slate-200 bg-white p-1 shadow-sm">
                    <button type="button" id="ltab-en" onclick="switchLang('en')"
                            class="rounded-lg bg-orange-500 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition-colors">
                        EN &nbsp; English
                    </button>
                    <button type="button" id="ltab-ar" onclick="switchLang('ar')"
                            class="rounded-lg px-4 py-1.5 text-sm font-medium text-slate-500 transition-colors hover:text-slate-700">
                        AR &nbsp; عربي
                    </button>
                </div>
            </div>

            <div class="p-6">
                <div id="lpanel-en" class="lang-panel space-y-5">
                    <div>
                        <label for="label_en" class="{{ $label }}">Branch Label (English)</label>
                        <input id="label_en" name="label[en]" type="text"
                               value="{{ old('label.en') }}"
                               class="{{ $input }}" placeholder="e.g. Downtown, Mall Branch">
                        @error('label.en') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="addr_en" class="{{ $label }}">Full Address (English) <span class="text-red-500">*</span></label>
                        <input id="addr_en" name="address_text[en]" type="text"
                               value="{{ old('address_text.en') }}"
                               class="{{ $input }}" placeholder="123 Main St, City, State 12345">
                        @error('address_text.en') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div id="lpanel-ar" class="lang-panel hidden space-y-5" dir="rtl">
                    <div>
                        <label for="label_ar" class="{{ $label }}">تسمية الفرع (عربي)</label>
                        <input id="label_ar" name="label[ar]" type="text"
                               value="{{ old('label.ar') }}"
                               class="{{ $input }}" placeholder="مثال: وسط المدينة">
                        @error('label.ar') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="addr_ar" class="{{ $label }}">العنوان الكامل (عربي)</label>
                        <input id="addr_ar" name="address_text[ar]" type="text"
                               value="{{ old('address_text.ar') }}"
                               class="{{ $input }}" placeholder="١٢٣ شارع الرئيسي، المدينة">
                        @error('address_text.ar') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- GPS --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">GPS Coordinates</h2>
                <p class="mt-0.5 text-sm text-slate-500">Used for geospatial nearby-branch queries.</p>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">
                <div>
                    <label for="lat" class="{{ $label }}">Latitude <span class="text-red-500">*</span></label>
                    <input id="lat" name="lat" type="number" step="any" value="{{ old('lat') }}" class="{{ $input }}" placeholder="40.71280">
                    @error('lat') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="lng" class="{{ $label }}">Longitude <span class="text-red-500">*</span></label>
                    <input id="lng" name="lng" type="number" step="any" value="{{ old('lng') }}" class="{{ $input }}" placeholder="-74.00600">
                    @error('lng') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="delivery_radius" class="{{ $label }}">Delivery Radius (km) <span class="text-red-500">*</span></label>
                    <input id="delivery_radius" name="delivery_radius" type="number" min="1" max="65535" value="{{ old('delivery_radius', 10) }}" class="{{ $input }}">
                    @error('delivery_radius') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Hours --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Hours &amp; Availability</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
                <div>
                    <label for="opens_at" class="{{ $label }}">Opening Time <span class="text-red-500">*</span></label>
                    <input id="opens_at" name="opens_at" type="time" value="{{ old('opens_at', '09:00') }}" class="{{ $input }}">
                    @error('opens_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="closes_at" class="{{ $label }}">Closing Time <span class="text-red-500">*</span></label>
                    <input id="closes_at" name="closes_at" type="time" value="{{ old('closes_at', '22:00') }}" class="{{ $input }}">
                    @error('closes_at') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 space-y-4">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-orange-500" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-slate-700">Branch is Active</span>
                            <p class="text-xs text-slate-500">Inactive branches are hidden from customers.</p>
                        </div>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="accept_orders" value="0">
                        <input type="checkbox" name="accept_orders" value="1" class="mt-0.5 h-4 w-4 rounded border-slate-300 accent-orange-500" {{ old('accept_orders', true) ? 'checked' : '' }}>
                        <div>
                            <span class="text-sm font-medium text-slate-700">Accept Orders</span>
                            <p class="text-xs text-slate-500">Toggle off to pause order intake without deactivating the branch.</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
            <a href="{{ route('admin.branches.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Cancel</a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">Create Branch</button>
        </div>

    </div>
</form>

@push('scripts')
<script>
function switchLang(lang) {
    document.querySelectorAll('.lang-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('lpanel-' + lang).classList.remove('hidden');
    ['en','ar'].forEach(l => {
        const tab = document.getElementById('ltab-' + l);
        if (l === lang) {
            tab.classList.add('bg-orange-500','text-white','shadow-sm');
            tab.classList.remove('text-slate-500');
        } else {
            tab.classList.remove('bg-orange-500','text-white','shadow-sm');
            tab.classList.add('text-slate-500');
        }
    });
}
document.addEventListener('DOMContentLoaded', () => {
    const ar = document.getElementById('lpanel-ar');
    if (ar && ar.querySelector('.text-red-500')) switchLang('ar');
});
</script>
@endpush

@endsection
