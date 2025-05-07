<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Models\UserModel as User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    /**
     * @inheritDoc
     */
    public function create(UserData $userData)
    {
        return User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
        ]);
    }
}