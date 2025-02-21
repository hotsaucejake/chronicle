<?php

use App\Data\UserData;

test('UserData instantiation', function () {
    $data = new UserData(id: '1', username: 'johndoe', email: 'john@example.com');

    $this->assertEquals('1', $data->id);
    $this->assertEquals('johndoe', $data->username);
    $this->assertEquals('john@example.com', $data->email);
});
