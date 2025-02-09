<?php

namespace App\Contracts\Services;

use App\Data\RegisterUserData;
use App\Models\User;

interface UserServiceInterface
{
    public function registerUser(RegisterUserData $data): User;
}
