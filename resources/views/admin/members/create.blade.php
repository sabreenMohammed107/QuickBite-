@extends('layouts.dashboard')
@section('title', 'Add Staff Member')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
    <a href="{{ route('admin.members.index') }}" class="hover:text-orange-600 transition-colors">Staff Members</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Add New</span>
</div>

@php
    $input   = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $label   = 'block text-sm font-medium text-slate-700';
    $select  = 'mt-1.5 block w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 transition';
    $errText = 'mt-1.5 text-xs text-red-500';
@endphp

<form method="POST" action="{{ route('admin.members.store') }}">
    @csrf

    {{-- ── User Account ─────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">User Account</h2>
            <p class="mt-0.5 text-sm text-slate-500">A new user account will be created with these credentials.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">

            {{-- Name --}}
            <div class="sm:col-span-2">
                <label for="name" class="{{ $label }}">Full Name <span class="text-red-500">*</span></label>
                <input id="name" name="name" type="text"
                       value="{{ old('name') }}"
                       class="{{ $input }}" placeholder="e.g. Jane Smith">
                @error('name') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="{{ $label }}">Email Address <span class="text-red-500">*</span></label>
                <input id="email" name="email" type="email"
                       value="{{ old('email') }}"
                       class="{{ $input }}" placeholder="jane@example.com">
                @error('email') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="{{ $label }}">Password <span class="text-red-500">*</span></label>
                <input id="password" name="password" type="password"
                       class="{{ $input }}" placeholder="Min 8 chars, mixed case + number">
                @error('password') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- System Role --}}
            <div class="sm:col-span-2">
                <label for="user_role" class="{{ $label }}">System Role <span class="text-red-500">*</span></label>
                <select id="user_role" name="user_role" class="{{ $select }}">
                    <option value="">Select system role…</option>
                    <option value="restaurant_owner" {{ old('user_role', 'restaurant_owner') === 'restaurant_owner' ? 'selected' : '' }}>Restaurant Owner / Staff</option>
                    <option value="delivery_agent"   {{ old('user_role') === 'delivery_agent'   ? 'selected' : '' }}>Delivery Agent</option>
                    <option value="customer"         {{ old('user_role') === 'customer'         ? 'selected' : '' }}>Customer</option>
                </select>
                <p class="mt-1 text-xs text-slate-400">Controls which parts of the system the user can access at the platform level.</p>
                @error('user_role') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

        </div>
    </div>

    {{-- ── Restaurant Assignment ────────────────────────────────── --}}
    <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Restaurant Assignment</h2>
            <p class="mt-0.5 text-sm text-slate-500">Assign this user to a restaurant and set their operational role.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-3">

            {{-- Restaurant --}}
            <div class="sm:col-span-2">
                <label for="restaurant_id" class="{{ $label }}">Restaurant <span class="text-red-500">*</span></label>
                <select id="restaurant_id" name="restaurant_id" class="{{ $select }}">
                    <option value="">Select restaurant…</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                            {{ $restaurant->name }} ({{ $restaurant->primary_country }})
                        </option>
                    @endforeach
                </select>
                @error('restaurant_id') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Member Role --}}
            <div>
                <label for="role" class="{{ $label }}">Member Role <span class="text-red-500">*</span></label>
                <select id="role" name="role" class="{{ $select }}">
                    <option value="">Select role…</option>
                    <option value="owner"   {{ old('role') === 'owner'   ? 'selected' : '' }}>Owner</option>
                    <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                    <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                    <option value="staff"   {{ old('role') === 'staff'   ? 'selected' : '' }}>Staff</option>
                </select>
                @error('role') <p class="{{ $errText }}">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
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

    {{-- ── Permissions ──────────────────────────────────────────── --}}
    <div class="mt-5 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-800">Permission Scopes</h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Select which capabilities this member has within the assigned restaurant.
                <span class="font-medium text-orange-600">Global Admins inherit all permissions and bypass these checks.</span>
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($permissionGroups as $groupLabel => $permissions)
                <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $groupLabel }}</p>
                    <div class="space-y-2.5">
                        @foreach($permissions as $key => $label)
                            <label class="flex cursor-pointer items-center gap-2.5">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $key }}"
                                       {{ in_array($key, old('permissions', []) ?: []) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500/30">
                                <span class="text-sm text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form footer --}}
        <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50/60 px-6 py-4">
            <a href="{{ route('admin.members.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-orange-500 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-orange-600">
                Create & Assign
            </button>
        </div>
    </div>
</form>

@endsection
