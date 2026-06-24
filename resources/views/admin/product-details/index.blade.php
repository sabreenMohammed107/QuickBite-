@extends('layouts.dashboard')
@section('title', 'Branch Catalog')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Branch Catalog</h2>
        <p class="mt-0.5 text-sm text-slate-500">Per-branch price, stock, and availability overrides for each product.</p>
    </div>
    <a href="{{ route('admin.product-details.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Entry
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Product</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Branch</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Price</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Stock</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Available</th>
                    <th class="relative px-6 py-3.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($details as $detail)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">{{ $detail->product?->name ?? '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $detail->branch?->restaurant?->name ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700">{{ $detail->branch?->label ?? $detail->branch?->address_text ?? '—' }}</p>
                            <p class="text-xs text-slate-400">{{ $detail->branch?->country_code }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-semibold text-slate-800">
                                ${{ number_format($detail->price, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm {{ $detail->stock === 0 ? 'font-semibold text-red-500' : 'text-slate-700' }}">
                                {{ $detail->stock }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                         {{ $detail->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $detail->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.product-details.edit', $detail) }}"
                                   class="text-sm font-medium text-slate-600 transition-colors hover:text-orange-600">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.product-details.destroy', $detail) }}"
                                      onsubmit="return confirm('Remove this catalog entry?')">
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
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="mx-auto mb-3 h-12 w-12 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                            </svg>
                            <p class="text-sm text-slate-500">No catalog entries yet.</p>
                            <a href="{{ route('admin.product-details.create') }}"
                               class="mt-2 inline-block text-sm font-medium text-orange-500 hover:text-orange-600">
                                Link a product to a branch →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($details->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $details->links() }}
        </div>
    @endif
</div>

@endsection
