<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class DocumentCreationData extends Data
{
    public function __construct(
        public ?string $content = null,
        public ?Carbon $expires_at = null,
    ) {}
}
