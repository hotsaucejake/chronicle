<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class DocumentCreationData extends Data
{
    public function __construct(
        public ?string $content = null,
    ) {}
}
