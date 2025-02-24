<?php

namespace App\Events\Document\Spatie;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SpatieDocumentEdited extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $new_content,
        public int $previous_version, // optimistic concurrency
        public int $editor_id,
    ) {}
}
