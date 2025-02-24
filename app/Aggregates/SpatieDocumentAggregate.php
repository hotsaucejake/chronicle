<?php

namespace App\Aggregates;

use App\Events\Document\Spatie\SpatieDocumentCreated;
use App\Events\Document\Spatie\SpatieDocumentEdited;
use App\Events\Document\Spatie\SpatieDocumentLocked;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class SpatieDocumentAggregate extends AggregateRoot
{
    public function createSpatieDocument(array $spatieDocumentAttributes): self
    {
        $this->recordThat(new SpatieDocumentCreated($spatieDocumentAttributes));

        return $this;
    }

    public function editSpatieDocument(string $uuid, string $newContent, int $previousVersion, int $editorId): self
    {
        $this->recordThat(new SpatieDocumentEdited($uuid, $newContent, $previousVersion, $editorId));

        return $this;
    }

    public function lockSpatieDocument(string $uuid): self
    {
        $this->recordThat(new SpatieDocumentLocked($uuid));

        return $this;
    }
}
