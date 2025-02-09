<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\AlphaDash;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class RegisterUserData extends Data
{
    public function __construct(
        #[Required]
        #[Min(3)]
        #[Max(255)]
        #[AlphaDash]
        #[Unique('users,username')]
        public string $username,

        #[Nullable]
        #[Email]
        #[Max(255)]
        #[Unique('users,email')]
        public ?string $email,

        #[Required]
        #[Min(8)]
        #[Confirmed]
        public string $password,
    ) {}
}
