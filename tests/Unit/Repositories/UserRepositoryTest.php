<?php

use App\Contracts\Repositories\UserRepositoryInterface;
use App\Models\User;

beforeEach(function () {
    $this->userRepository = app(UserRepositoryInterface::class);
});

test('can create a user', function () {
    $data = [
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'password' => bcrypt('password'),
    ];

    $user = $this->userRepository->create($data);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->username)->toEqual('testuser')
        ->and($user->email)->toEqual('testuser@example.com');
});
