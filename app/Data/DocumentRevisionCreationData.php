<?php

namespace App\Data;

use Carbon\CarbonImmutable;
use Glhd\Bits\Snowflake;
use Spatie\LaravelData\Data;

class DocumentRevisionCreationData extends Data
{
    public function __construct(
        public Snowflake $document_id,
        public int $version,
        public string $content,

        public ?Snowflake $edited_by_user_id,

        public ?CarbonImmutable $edited_at
    ) {}
}
