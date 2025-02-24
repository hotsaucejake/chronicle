<?php

namespace App\Events\Document\Spatie;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SpatieDocumentLocked extends ShouldBeStored
{
    public function __construct(public string $uuid) {}
}
