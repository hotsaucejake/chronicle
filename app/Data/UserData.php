<?php

namespace App\Data;

use Glhd\Bits\Snowflake;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        #[Required]
        public Snowflake $id,

        #[Required]
        public string $username,

        #[Nullable]
        public ?string $email
    ) {}
}
