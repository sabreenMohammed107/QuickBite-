<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Domains\Auth\Actions\RegisterUserAction;
use App\Domains\Auth\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function __invoke(Request $request, RegisterUserAction $action): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:mysql_core.users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['sometimes', Rule::enum(UserRole::class)],
        ]);

        $user = $action->execute(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: isset($data['role']) ? UserRole::from($data['role']) : UserRole::Customer,
        );

        return response()->json(['data' => $user], 201);
    }
}
