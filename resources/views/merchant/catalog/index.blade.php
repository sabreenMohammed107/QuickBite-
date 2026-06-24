@extends('merchant.layouts.app')
@section('title', 'Branch Catalog')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Branch Catalog</h2>
        <p class="mt-0.5 text-sm text-slate-500">Set prices, stock, and availability for products per branch.</p>
    </div>
    <a href="{{ route('merchant.catalog.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">
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
                        <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $detail->product->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $detail->branch->label ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">${{ number_format($detail->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $detail->stock }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $detail->is_available ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $detail->is_available ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('merchant.catalog.edit', $detail) }}"
                               class="text-sm font-medium text-slate-600 transition-colors hover:text-indigo-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500">
                            No catalog entries yet.
                            <a href="{{ route('merchant.catalog.create') }}" class="ml-1 font-medium text-indigo-600 hover:text-indigo-700">Add your first →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($details->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">{{ $details->links() }}</div>
    @endif
</div>

@endsection
