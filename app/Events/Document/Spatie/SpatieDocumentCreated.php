<?php

namespace App\Events\Document\Spatie;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class SpatieDocumentCreated extends ShouldBeStored
{
    public function __construct(public array $spatieDocumentAttributes) {}
}
