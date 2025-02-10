<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;

class DocumentRevisionCreationData extends Data
{
    public function __construct(
        public string $document_id,
        public int $version,
        public string $content,

        public ?string $edited_by_user_id,

        public ?CarbonImmutable $edited_at
    ) {}
}
