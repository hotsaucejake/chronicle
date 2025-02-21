<?php

namespace App\Data;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class VerbsDocumentCreationData extends Data
{
    public function __construct(
        public ?string $content = null,
        public bool $is_locked = false,
        public ?Carbon $expires_at = null,
    ) {}
}
