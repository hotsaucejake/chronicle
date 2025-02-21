<?php

use App\Models\User;

test('get filament name returns username', function () {
    $user = new User(['username' => 'johndoe']);
    $this->assertEquals('johndoe', $user->getFilamentName());
});

test('route key name is username', function () {
    $user = new User(['username' => 'johndoe']);
    $this->assertEquals('username', $user->getRouteKeyName());
});
