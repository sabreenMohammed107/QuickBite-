@extends('merchant.layouts.app')
@section('title', 'Merchant Dashboard')

@section('content')

{{-- Welcome header --}}
<div class="mb-6">
    <h2 class="text-xl font-semibold text-slate-800">
        Welcome back, {{ auth()->user()->name }}
    </h2>
    <p class="mt-0.5 text-sm text-slate-500">
        Managing
        <span class="font-medium text-slate-700">{{ $restaurant->name }}</span>
        &middot;
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
            {{ $restaurant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
            {{ ucfirst($restaurant->status) }}
        </span>
    </p>
</div>

{{-- Stats row --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

    {{-- Branches --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['branches'] }}</p>
                    <p class="text-sm text-slate-500">Branches</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['products'] }}</p>
                    <p class="text-sm text-slate-500">Products</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Active staff --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="p-5">
            <div class="flex items-center gap-4">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-teal-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['members'] }}</p>
                    <p class="text-sm text-slate-500">Active Staff</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Restaurant info card --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="border-b border-slate-200 px-6 py-4">
        <h3 class="text-sm font-semibold text-slate-800">Restaurant Details</h3>
    </div>
    <div class="grid grid-cols-2 gap-6 p-6 sm:grid-cols-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Name</p>
            <p class="mt-1 text-sm font-medium text-slate-800">{{ $restaurant->name }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Country</p>
            <p class="mt-1 text-sm text-slate-700">{{ $restaurant->primary_country }}</p>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Status</p>
            <span class="mt-1 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                {{ $restaurant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                {{ ucfirst($restaurant->status) }}
            </span>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Joined</p>
            <p class="mt-1 text-sm text-slate-700">{{ $restaurant->created_at->format('M j, Y') }}</p>
        </div>
    </div>
</div>

@endsection
