<?php

use App\Contracts\Services\UserServiceInterface;
use App\Data\RegisterUserData;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->userService = app(UserServiceInterface::class);
});

it('registers a user and hashes the password', function () {
    // Create a RegisterUserData instance.
    $data = RegisterUserData::from([
        // You can omit the id if your Snowflake trait auto-generates it.
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'password' => 'password',
    ]);

    $user = $this->userService->registerUser($data);

    expect($user)->toBeInstanceOf(User::class)
        ->and(Hash::check('password', $user->password))->toBeTrue()
        ->and($user->username)->toEqual('testuser');
});
