@extends('layouts.dashboard')
@section('title', 'Branches')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Branches</h2>
        <p class="mt-0.5 text-sm text-slate-500">Geographic locations for each restaurant brand.</p>
    </div>
    <a href="{{ route('admin.branches.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Branch
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Branch</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Restaurant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Coordinates</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Hours</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="relative px-6 py-3.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($branches as $branch)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-slate-800">{{ $branch->label ?? $branch->address_text }}</p>
                            <p class="mt-0.5 max-w-[240px] truncate text-xs text-slate-400">
                                {{ $branch->country_code }} · {{ $branch->address_text }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-700">{{ $branch->restaurant?->name ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-slate-500">
                                {{ number_format($branch->lat, 5) }},
                                {{ number_format($branch->lng, 5) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $branch->opens_at }} – {{ $branch->closes_at }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex w-fit items-center rounded-full px-2.5 py-1 text-xs font-medium
                                             {{ $branch->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($branch->accept_orders)
                                    <span class="inline-flex w-fit items-center rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        Accepts Orders
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('admin.branches.edit', $branch) }}"
                                   class="text-sm font-medium text-slate-600 transition-colors hover:text-orange-600">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}"
                                      onsubmit="return confirm('Delete this branch? This cannot be undone.')">
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            <p class="text-sm text-slate-500">No branches yet.</p>
                            <a href="{{ route('admin.branches.create') }}"
                               class="mt-2 inline-block text-sm font-medium text-orange-500 hover:text-orange-600">
                                Add your first branch →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($branches->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $branches->links() }}
        </div>
    @endif
</div>

@endsection
