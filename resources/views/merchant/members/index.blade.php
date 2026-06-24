@extends('merchant.layouts.app')
@section('title', 'Staff Members')

@section('content')

<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-semibold text-slate-800">Staff Members</h2>
        <p class="mt-0.5 text-sm text-slate-500">Manage who has access to your restaurant and what they can do.</p>
    </div>
    <a href="{{ route('merchant.members.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
        </svg>
        Add Member
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Staff</th>
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
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-600">
                                    {{ strtoupper(substr($member->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-800">{{ $member->user->name ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $member->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badge = match($member->role->value ?? '') {
                                    'owner'   => 'bg-purple-100 text-purple-700',
                                    'manager' => 'bg-blue-100 text-blue-700',
                                    'cashier' => 'bg-teal-100 text-teal-700',
                                    default   => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $badge }}">
                                {{ ucfirst($member->role->value ?? '—') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium
                                {{ $member->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ count($member->permissions ?? []) }} scopes</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('merchant.members.edit', $member) }}"
                               class="text-sm font-medium text-slate-600 transition-colors hover:text-indigo-600">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-500">
                            No staff members yet.
                            <a href="{{ route('merchant.members.create') }}" class="ml-1 font-medium text-indigo-600 hover:text-indigo-700">Add your first →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
        <div class="border-t border-slate-200 px-6 py-4">{{ $members->links() }}</div>
    @endif
</div>

@endsection
