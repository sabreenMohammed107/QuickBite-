@extends('layouts.dashboard')
@section('title', 'Add Restaurant')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.restaurants.index') }}" class="hover:text-orange-600 transition-colors">Restaurants</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.restaurants.store') }}">
    @csrf

    <div class="space-y-5">

        {{-- Translatable: Restaurant Name --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Restaurant Name</h2>
                <p class="mt-0.5 text-sm text-slate-500">Enter the name in both languages. English is required.</p>
            </div>

            {{-- Language tab --}}
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
                <div id="lpanel-en" class="lang-panel">
                    <div>
                        <label for="name_en" class="{{ $label }}">Name (English) <span class="text-red-500">*</span></label>
                        <input id="name_en" name="name[en]" type="text"
                               value="{{ old('name.en') }}"
                               class="{{ $input }}" placeholder="e.g. Burger Palace">
                        @error('name.en') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div id="lpanel-ar" class="lang-panel hidden" dir="rtl">
                    <div>
                        <label for="name_ar" class="{{ $label }}">الاسم (عربي)</label>
                        <input id="name_ar" name="name[ar]" type="text"
                               value="{{ old('name.ar') }}"
                               class="{{ $input }}" placeholder="مثال: قصر البرجر">
                        @error('name.ar') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Non-translatable fields --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Settings</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

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

                <div>
                    <label for="primary_country" class="{{ $label }}">Primary Country Code <span class="text-red-500">*</span></label>
                    <input id="primary_country" name="primary_country" type="text"
                           value="{{ old('primary_country') }}"
                           class="{{ $input }}" placeholder="US" maxlength="2">
                    <p class="mt-1 text-xs text-slate-400">2-letter ISO 3166-1 alpha-2 (e.g. US, AE).</p>
                    @error('primary_country') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="logo_url" class="{{ $label }}">Logo URL <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input id="logo_url" name="logo_url" type="url"
                           value="{{ old('logo_url') }}"
                           class="{{ $input }}" placeholder="https://…">
                    @error('logo_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
            <a href="{{ route('admin.restaurants.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Cancel</a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                Create Restaurant
            </button>
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
