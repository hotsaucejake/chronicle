<?php

namespace App\Events\Document\Spatie;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SpatieDocumentExpirationExtended extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $new_expires_at
    ) {}
}
