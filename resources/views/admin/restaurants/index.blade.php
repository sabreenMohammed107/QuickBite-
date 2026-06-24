@extends('layouts.dashboard')
@section('title', 'Restaurants')

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Restaurants</h2>
        <p class="mt-0.5 text-sm text-slate-500">Manage your restaurant brands and their global settings.</p>
    </div>
    <a href="{{ route('admin.restaurants.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Restaurant
    </a>
</div>

{{-- Table card --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Restaurant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Country</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                    <th class="relative px-6 py-3.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($restaurants as $restaurant)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($restaurant->logo_url)
                                    <img src="{{ $restaurant->logo_url }}" alt=""
                                         class="h-9 w-9 rounded-lg border border-slate-200 object-cover">
                                @else
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-sm font-bold text-orange-600">
                                        {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                                    </div>
                                @endif
                                <p class="text-sm font-medium text-slate-800">{{ $restaurant->name }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">
                                {{ $restaurant->primary_country }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($restaurant->status) {
                                    'active'         => 'bg-green-100 text-green-700',
                                    'inactive'       => 'bg-slate-100 text-slate-500',
                                    'pending_review' => 'bg-amber-100 text-amber-700',
                                    default          => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">
                                {{ ucfirst(str_replace('_', ' ', $restaurant->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $restaurant->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.restaurants.edit', $restaurant) }}"
                                   class="text-sm font-medium text-slate-600 transition-colors hover:text-orange-600">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.restaurants.destroy', $restaurant) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($restaurant->name) }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="text-sm font-medium text-red-400 transition-colors hover:text-red-600">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="mx-auto mb-3 h-12 w-12 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016 2.993 2.993 0 002.25-1.016 3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                            </svg>
                            <p class="text-sm text-slate-500">No restaurants yet.</p>
                            <a href="{{ route('admin.restaurants.create') }}"
                               class="mt-2 inline-block text-sm font-medium text-orange-500 hover:text-orange-600">
                                Add your first restaurant →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($restaurants->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $restaurants->links() }}
        </div>
    @endif
</div>

@endsection
