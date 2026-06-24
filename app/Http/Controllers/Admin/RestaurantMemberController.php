<?php

namespace App\Http\Controllers\Admin;

use App\Domains\Auth\Enums\UserRole;
use App\Domains\Auth\Enums\UserStatus;
use App\Domains\Restaurant\Enums\MemberRole;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RestaurantMemberController extends Controller
{
    public const PERMISSION_GROUPS = [
        'Orders' => [
            'orders.view'   => 'View Orders',
            'orders.create' => 'Create Orders',
            'orders.update' => 'Update Orders',
            'orders.cancel' => 'Cancel Orders',
        ],
        'Products' => [
            'products.view'   => 'View Products',
            'products.create' => 'Create Products',
            'products.edit'   => 'Edit Products',
            'products.delete' => 'Delete Products',
        ],
        'Catalog' => [
            'catalog.view'   => 'View Branch Catalog',
            'catalog.create' => 'Add Catalog Entries',
            'catalog.edit'   => 'Edit Prices & Stock',
            'catalog.delete' => 'Remove Catalog Entries',
        ],
        'Branches' => [
            'branches.view'   => 'View Branches',
            'branches.create' => 'Create Branches',
            'branches.edit'   => 'Edit Branches',
            'branches.delete' => 'Delete Branches',
        ],
        'Staff' => [
            'staff.view'   => 'View Staff',
            'staff.manage' => 'Manage Staff',
        ],
        'Reports' => [
            'reports.view' => 'View Reports',
        ],
    ];

    public function index()
    {
        $members = RestaurantMember::with(['user', 'restaurant'])
            ->latest()
            ->paginate(20);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        $restaurants      = Restaurant::orderBy('name')->get();
        $permissionGroups = self::PERMISSION_GROUPS;

        return view('admin.members.create', compact('restaurants', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $allPermissionKeys = array_keys(array_merge(...array_values(self::PERMISSION_GROUPS)));

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:mysql_core.users,email'],
            'password'      => ['required', Password::min(8)->mixedCase()->numbers()],
            'user_role'     => ['required', Rule::in(['restaurant_owner', 'delivery_agent', 'customer'])],
            'restaurant_id' => ['required', 'exists:mysql_core.restaurants,id'],
            'role'          => ['required', Rule::in(array_column(MemberRole::cases(), 'value'))],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allPermissionKeys)],
        ]);

        DB::connection('mysql_core')->transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => UserRole::from($data['user_role']),
                'status'   => UserStatus::Active,
            ]);

            RestaurantMember::create([
                'user_id'       => $user->id,
                'restaurant_id' => $data['restaurant_id'],
                'role'          => $data['role'],
                'status'        => $data['status'],
                'permissions'   => $data['permissions'] ?? [],
            ]);
        });

        return redirect()->route('admin.members.index')
            ->with('success', "Staff member \"{$data['name']}\" created and assigned successfully.");
    }

    public function edit(RestaurantMember $member)
    {
        $member->load(['user', 'restaurant']);
        $permissionGroups = self::PERMISSION_GROUPS;

        return view('admin.members.edit', compact('member', 'permissionGroups'));
    }

    public function update(Request $request, RestaurantMember $member)
    {
        $allPermissionKeys = array_keys(array_merge(...array_values(self::PERMISSION_GROUPS)));

        $data = $request->validate([
            'role'          => ['required', Rule::in(array_column(MemberRole::cases(), 'value'))],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allPermissionKeys)],
        ]);

        $member->update([
            'role'        => $data['role'],
            'status'      => $data['status'],
            'permissions' => $data['permissions'] ?? [],
        ]);

        return redirect()->route('admin.members.index')
            ->with('success', "Member \"{$member->user->name}\" updated successfully.");
    }

    public function destroy(RestaurantMember $member)
    {
        $name = $member->user->name;
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', "Staff member \"{$name}\" removed.");
    }
}
