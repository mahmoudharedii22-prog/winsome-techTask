<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService extends BaseService
{
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $user;
    }

    public function login(array $credentials): User
    {

        if (! Auth::attempt($credentials)) {
            abort(401, 'Invalid credentials');
        }

        $user = $this->model::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return $user;
    }

    public function CreateAndAddToken(array $data): User
    {
        $user = $this->store($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return $user;
    }
}
