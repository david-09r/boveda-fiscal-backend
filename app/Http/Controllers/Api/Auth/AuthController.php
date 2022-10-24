<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Role;
use App\Models\User;
use App\Utils\Enum\EnumForRole;
use App\Utils\Enum\EnumForStatus;
use App\Utils\Enum\EnumForUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse|string
    {
        try {
            $data = $request->validated();
            $role = Role::where('name', EnumForRole::ROLE2)->first();

            $data['role_id'] = $role->id;
            $user = User::create($data);

            $success = [
                'message' => 'Registered User!',
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $user->createToken('AuthUserIndividual')->plainTextToken,
            ];

            return bodyResponse(EnumForStatus::OK, $success);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $auth = Auth::attempt([
                'email' => $data['email'],
                'password' => $data['password']
            ]);

            if (!$auth) {
                return bodyResponse(EnumForStatus::UNAUTHORIZED);
            }

            $user = Auth::user();
            $success = [
                'name' => $user['name'] .'Logging!',
                'access_token' => $user->createToken('AuthUserIndividual')->plainTextToken,
            ];
            return bodyResponse(EnumForStatus::OK, $success);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function verifyRoleUserAndPermission($user)
    {
        try {
            if (!isset($user->role_id)) {
                return null;
            }

            $role = $user->role ?? null;

            if ($role === null) {
                return null;
            }

            $role = $role['name'] ?? null;

            if ($role === EnumForRole::ROLE1) {
                return EnumForRole::ROLE1;
            } else if ($role === EnumForRole::ROLE2) {
                return EnumForRole::ROLE2;
            } else if ($role === EnumForRole::ROLE3) {
                return EnumForRole::ROLE3;
            }else {
                return null;
            }

        } catch (\Exception $e) {
            return new \Exception($e->getMessage());
        }
    }
}
