<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        #[Required]
        public string $id,

        #[Required]
        public string $username,

        #[Nullable]
        public ?string $email
    ) {}
}
