@extends('layouts.dashboard')
@section('title', 'Staff Members')

@section('content')

{{-- Page header --}}
<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Staff Members</h2>
        <p class="mt-0.5 text-sm text-slate-500">Create user accounts, assign them to restaurants, and manage per-restaurant permissions.</p>
    </div>
    <a href="{{ route('admin.members.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Staff Member
    </a>
</div>

{{-- Table card --}}
<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">User</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Restaurant</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Role</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Permissions</th>
                    <th class="relative px-6 py-3.5"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($members as $member)
                    <tr class="transition-colors hover:bg-slate-50/70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-100 text-sm font-bold text-orange-600">
                                    {{ strtoupper(substr($member->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800">{{ $member->user->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $member->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-700">
                            {{ $member->restaurant->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $roleBadge = match($member->role->value ?? '') {
                                    'owner'   => 'bg-purple-100 text-purple-700',
                                    'manager' => 'bg-blue-100 text-blue-700',
                                    'cashier' => 'bg-teal-100 text-teal-700',
                                    'staff'   => 'bg-slate-100 text-slate-600',
                                    default   => 'bg-slate-100 text-slate-500',
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $roleBadge }}">
                                {{ ucfirst($member->role->value ?? '—') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusBadge = $member->status === 'active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-slate-100 text-slate-500';
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $statusBadge }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ count($member->permissions ?? []) }} scopes
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.members.edit', $member) }}"
                               class="text-sm font-medium text-slate-600 transition-colors hover:text-orange-600">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="mx-auto mb-3 h-12 w-12 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <p class="text-sm text-slate-500">No staff members yet.</p>
                            <a href="{{ route('admin.members.create') }}"
                               class="mt-2 inline-block text-sm font-medium text-orange-500 hover:text-orange-600">
                                Add your first staff member →
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($members->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">
            {{ $members->links() }}
        </div>
    @endif
</div>

@endsection
