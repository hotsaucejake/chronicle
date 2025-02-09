<?php

namespace App\Services;

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Data\RegisterUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function registerUser(RegisterUserData $data): User
    {
        $payload = $data->toArray();
        $payload['password'] = Hash::make($payload['password']);

        return $this->userRepository->create($payload);
    }
}
