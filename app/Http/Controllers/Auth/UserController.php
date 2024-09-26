<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $searchTerm = $request->get('search', '');

        $query = User::query();

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $user = $query->paginate($perPage);
        return UserResource::collection($user)->additional([
            'success' => true,
            'code' => 200,
            'message' => 'Users retrieved successfully',
        ]);
    }

    public function show(User $user)
    {
        $data = new UserResource($user);
        return $this->success($data, "User retrieved successfully");
    }

    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated['password'] = Hash::make($validated['password']);
            $user = User::create($validated);
            DB::commit();
            return $this->success($user, "User created successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to create User");
        }
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }
            $user->update($validated);
            DB::commit();
            return $this->success($user, "User updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to update User");
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();
            return $this->success($user, "User deleted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->failure($e->getMessage(), "Failed to delete User");
        }
    }

    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->user()->currentAccessToken()->delete();
            DB::commit();
            return $this->success($request, "Logged out successfully");
        } catch (\Exception $e) {
            return $this->failure($e->getMessage(), "Failed to logout");
        }
    }
}
