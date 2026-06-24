@extends('merchant.layouts.app')
@section('title', 'Add Staff Member')

@section('content')

<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('merchant.members.index') }}" class="hover:text-indigo-600 transition-colors">Staff Members</a>
    <span>/</span>
    <span class="font-medium text-slate-800">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('merchant.members.store') }}">
    @csrf

    {{-- User account --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">User Account</h2>
            <p class="mt-0.5 text-sm text-slate-500">A new login account will be created for this staff member.</p>
        </div>
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Full Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" class="{{ $input }}" placeholder="e.g. John Smith">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="{{ $label }}">Email <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" class="{{ $input }}" placeholder="john@example.com">
                @error('email') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="{{ $label }}">Password <span class="text-red-500">*</span></label>
                <input id="password" name="password" type="password" class="{{ $input }}" placeholder="Min 8 chars, mixed case + number">
                @error('password') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="{{ $label }}">Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="{{ $select }}">
                    <option value="">Select role…</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="staff"   {{ old('role') === 'staff'   ? 'selected' : '' }}>Staff</option>
                </select>
                @error('role') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="{{ $label }}">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="{{ $select }}">
                    <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>

    {{-- Permissions --}}
    <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Permission Scopes</h2>
            <p class="mt-0.5 text-sm text-slate-500">Choose what this staff member can do within your restaurant.</p>
        </div>
        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($permissionGroups as $groupLabel => $permissions)
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $groupLabel }}</p>
                    <div class="space-y-2.5">
                        @foreach($permissions as $key => $permLabel)
                            <label class="flex cursor-pointer items-center gap-2.5">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                       {{ in_array($key, old('permissions', []) ?: []) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30">
                                <span class="text-sm text-slate-700">{{ $permLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <a href="{{ route('merchant.members.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit"
                    class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-colors">
                Add Member
            </button>
        </div>
    </div>
</form>

@endsection
