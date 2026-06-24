<?php

namespace App\Http\Controllers\Merchant;

use App\Domains\Auth\Enums\UserRole;
use App\Domains\Auth\Enums\UserStatus;
use App\Domains\Restaurant\Enums\MemberRole;
use App\Http\Controllers\Admin\RestaurantMemberController as PermissionSource;
use App\Http\Controllers\Controller;
use App\Models\RestaurantMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class MemberController extends Controller
{
    private function restaurantId(): int
    {
        return session('active_restaurant_id');
    }

    public function index()
    {
        $members = RestaurantMember::where('restaurant_id', $this->restaurantId())
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('merchant.members.index', compact('members'));
    }

    public function create()
    {
        $permissionGroups = PermissionSource::PERMISSION_GROUPS;

        return view('merchant.members.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $allKeys = array_keys(array_merge(...array_values(PermissionSource::PERMISSION_GROUPS)));

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', 'unique:mysql_core.users,email'],
            'password'      => ['required', Password::min(8)->mixedCase()->numbers()],
            'role'          => ['required', Rule::in(array_column(MemberRole::cases(), 'value'))],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allKeys)],
        ]);

        DB::connection('mysql_core')->transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => UserRole::RestaurantOwner,
                'status'   => UserStatus::Active,
            ]);

            RestaurantMember::create([
                'user_id'       => $user->id,
                'restaurant_id' => $this->restaurantId(),
                'role'          => $data['role'],
                'status'        => $data['status'],
                'permissions'   => $data['permissions'] ?? [],
            ]);
        });

        return redirect()->route('merchant.members.index')
            ->with('success', "Staff member \"{$data['name']}\" added.");
    }

    public function edit(RestaurantMember $member)
    {
        abort_unless($member->restaurant_id === $this->restaurantId(), 403);

        $member->load('user');
        $permissionGroups = PermissionSource::PERMISSION_GROUPS;

        return view('merchant.members.edit', compact('member', 'permissionGroups'));
    }

    public function update(Request $request, RestaurantMember $member)
    {
        abort_unless($member->restaurant_id === $this->restaurantId(), 403);

        $allKeys = array_keys(array_merge(...array_values(PermissionSource::PERMISSION_GROUPS)));

        $data = $request->validate([
            'role'          => ['required', Rule::in(array_column(MemberRole::cases(), 'value'))],
            'status'        => ['required', Rule::in(['active', 'inactive'])],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($allKeys)],
        ]);

        $member->update([
            'role'        => $data['role'],
            'status'      => $data['status'],
            'permissions' => $data['permissions'] ?? [],
        ]);

        return redirect()->route('merchant.members.index')
            ->with('success', "Member \"{$member->user->name}\" updated.");
    }

    public function destroy(RestaurantMember $member)
    {
        abort_unless($member->restaurant_id === $this->restaurantId(), 403);

        $name = $member->user->name;
        $member->delete();

        return redirect()->route('merchant.members.index')
            ->with('success', "Staff member \"{$name}\" removed.");
    }
}
