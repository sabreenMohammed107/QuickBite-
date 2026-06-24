@extends('layouts.dashboard')
@section('title', 'Products')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Products</h2>
        <p class="mt-0.5 text-sm text-slate-500">Global product catalog — prices and stock are set per branch.</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Product
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Product</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Restaurant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Description</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Added</th>
                    <th class="relative px-6 py-3.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($products as $product)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt=""
                                         class="h-10 w-10 rounded-lg border border-slate-200 object-cover">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-base font-bold text-slate-400">
                                        {{ strtoupper(substr($product->t('name'), 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-slate-800">{{ $product->t('name') }}</p>
                                    @if(!empty($product->name['ar']))
                                        <p class="text-xs text-slate-400" dir="rtl">{{ $product->name['ar'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $product->restaurant?->t('name') ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="max-w-[260px] truncate text-sm text-slate-500">
                                {{ $product->t('description') ?: '—' }}
                            </p>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $product->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="text-sm font-medium text-slate-600 transition-colors hover:text-orange-600">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($product->t('name')) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-400 transition-colors hover:text-red-600">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <p class="text-sm text-slate-500">No products yet.</p>
                            <a href="{{ route('admin.products.create') }}"
                               class="mt-2 inline-block text-sm font-medium text-orange-500 hover:text-orange-600">
                                Add your first product →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">{{ $products->links() }}</div>
    @endif
</div>

@endsection
