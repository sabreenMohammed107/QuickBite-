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

    <div class="space-y-5">

        {{-- Restaurant picker (non-translatable) --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Product Details</h2>
                <p class="mt-0.5 text-sm text-slate-500">Global catalog entry. Branch pricing is set in <a href="{{ route('admin.product-details.index') }}" class="text-orange-500 hover:underline">Branch Catalog</a>.</p>
            </div>
            <div class="p-6">
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
        </div>

        {{-- Translatable fields --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Name &amp; Description</h2>
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
                        <label for="name_en" class="{{ $label }}">Name (English) <span class="text-red-500">*</span></label>
                        <input id="name_en" name="name[en]" type="text"
                               value="{{ old('name.en') }}"
                               class="{{ $input }}" placeholder="e.g. Classic Cheeseburger">
                        @error('name.en') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="desc_en" class="{{ $label }}">Description (English)</label>
                        <textarea id="desc_en" name="description[en]" rows="3"
                                  class="{{ $input }} resize-none"
                                  placeholder="Short description…">{{ old('description.en') }}</textarea>
                        @error('description.en') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div id="lpanel-ar" class="lang-panel hidden space-y-5" dir="rtl">
                    <div>
                        <label for="name_ar" class="{{ $label }}">الاسم (عربي)</label>
                        <input id="name_ar" name="name[ar]" type="text"
                               value="{{ old('name.ar') }}"
                               class="{{ $input }}" placeholder="مثال: برجر الجبن الكلاسيكي">
                        @error('name.ar') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="desc_ar" class="{{ $label }}">الوصف (عربي)</label>
                        <textarea id="desc_ar" name="description[ar]" rows="3"
                                  class="{{ $input }} resize-none"
                                  placeholder="وصف مختصر…">{{ old('description.ar') }}</textarea>
                        @error('description.ar') <p class="{{ $errText }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Image --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-800">Media</h2>
            </div>
            <div class="p-6">
                <label for="image_url" class="{{ $label }}">Image URL <span class="text-slate-400 font-normal">(optional)</span></label>
                <input id="image_url" name="image_url" type="url"
                       value="{{ old('image_url') }}"
                       class="{{ $input }}" placeholder="https://…">
                @error('image_url') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">Cancel</a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">Create Product</button>
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
