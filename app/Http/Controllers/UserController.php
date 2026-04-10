<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(UserLoginRequest $request)
    {

        $user = $this->userService->login($request->validated());

        return ResponseHelper::successReponse(new UserResource($user), 'User logged in successfully', 200);
    }

    public function index()
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userService->index();

        return ResponseHelper::successReponse(UserResource::collection($users), 'Users retrieved successfully', 200);
    }

    public function store(CreateUserRequest $request)
    {
        $user = $this->userService->CreateAndAddToken($request->validated());

        return ResponseHelper::successReponse(new UserResource($user), 'User created successfully', 201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user = $this->userService->update($user, $request->validated());

        return ResponseHelper::successReponse(new UserResource($user), 'User updated successfully', 200);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $this->userService->destroy($user);

        return ResponseHelper::successReponse(null, 'User deleted successfully', 200);
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return ResponseHelper::successReponse(new UserResource($user), 'User retrieved successfully', 200);
    }

    public function forceDelete(int $user_id)
    {
        $this->authorize('forceDelete', User::class);
        $this->userService->forceDelete($user_id);

        return ResponseHelper::successReponse(null, 'User has been forcely deleted successfully', 200);
    }

    public function restore(int $user_id)
    {
        $this->authorize('restore', User::class);
        $this->userService->restore($user_id);

        return ResponseHelper::successReponse(null, 'User has been restored successfully', 200);
    }

    public function getDeletedUsers()
    {
        $this->authorize('viewAny', User::class);
        $users = $this->userService->showDeleted();

        return ResponseHelper::successReponse(UserResource::collection($users), 'Deleted users retrieved successfully', 200);
    }
}
