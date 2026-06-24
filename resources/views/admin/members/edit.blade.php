@extends('layouts.dashboard')
@section('title', 'Edit Staff Member')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.members.index') }}" class="hover:text-orange-600 transition-colors">Staff Members</a>
    <span>/</span>
    <span class="truncate font-medium text-slate-800">{{ $member->user->name }}</span>
</div>

@php
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
    $activePermissions = old('permissions', $member->permissions ?? []);
@endphp

<form method="POST" action="{{ route('admin.members.update', $member) }}">
    @csrf @method('PUT')

    {{-- ── User Info (read-only) ───────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">User Account</h2>
            <p class="mt-0.5 text-sm text-slate-500">User identity cannot be changed after creation.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Name</p>
                <p class="mt-1 text-sm font-medium text-slate-800">{{ $member->user->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Email</p>
                <p class="mt-1 text-sm text-slate-700">{{ $member->user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Restaurant</p>
                <p class="mt-1 text-sm text-slate-700">{{ $member->restaurant->name }}</p>
            </div>
        </div>
    </div>

    {{-- ── Assignment Settings ──────────────────────────────────── --}}
    <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Assignment Settings</h2>
            <p class="mt-0.5 text-sm text-slate-500">Update the member's role and activation status.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            {{-- Member Role --}}
            <div>
                <label for="role" class="{{ $label }}">Member Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="{{ $select }}">
                    <option value="owner"   {{ old('role', $member->role->value) === 'owner'   ? 'selected' : '' }}>Owner</option>
                    <option value="manager" {{ old('role', $member->role->value) === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="cashier" {{ old('role', $member->role->value) === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="staff"   {{ old('role', $member->role->value) === 'staff'   ? 'selected' : '' }}>Staff</option>
                </select>
                @error('role') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="{{ $label }}">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="{{ $select }}">
                    <option value="active"   {{ old('status', $member->status) === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $member->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>

    {{-- ── Permissions ──────────────────────────────────────────── --}}
    <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Permission Scopes</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Granular capabilities for this member within
                        <span class="font-medium text-slate-700">{{ $member->restaurant->name }}</span>.
                        <span class="font-medium text-orange-600">Global Admins bypass all checks.</span>
                    </p>
                </div>
                <div class="flex shrink-0 gap-2">
                    <button type="button" onclick="toggleAll(true)"
                            class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition-colors hover:bg-slate-50">
                        Select All
                    </button>
                    <button type="button" onclick="toggleAll(false)"
                            class="rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 transition-colors hover:bg-slate-50">
                        Clear All
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($permissionGroups as $groupLabel => $permissions)
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $groupLabel }}</p>
                    <div class="space-y-2.5">
                        @foreach($permissions as $key => $permLabel)
                            <label class="flex cursor-pointer items-center gap-2.5">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $key }}"
                                       {{ in_array($key, $activePermissions) ? 'checked' : '' }}
                                       class="permission-checkbox h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500/30">
                                <span class="text-sm text-slate-700">{{ $permLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form footer --}}
        <div class="flex items-center justify-between gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <button type="button"
                    onclick="if(confirm('Remove {{ addslashes($member->user->name) }} from {{ addslashes($member->restaurant->name) }}? This cannot be undone.')) document.getElementById('delete-form').submit()"
                    class="text-sm font-medium text-red-400 transition-colors hover:text-red-600">
                Remove member
            </button>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.members.index') }}"
                   class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>

<form id="delete-form" method="POST" action="{{ route('admin.members.destroy', $member) }}" class="hidden">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
    function toggleAll(checked) {
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = checked);
    }
</script>
@endpush

@endsection
