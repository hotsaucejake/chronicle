<?php

namespace App\Aggregates;

use App\Events\Document\Spatie\SpatieDocumentCreated;
use App\Events\Document\Spatie\SpatieDocumentEdited;
use App\Events\Document\Spatie\SpatieDocumentExpirationExtended;
use App\Events\Document\Spatie\SpatieDocumentLocked;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class SpatieDocumentAggregate extends AggregateRoot
{
    public function createSpatieDocument(array $spatieDocumentAttributes): self
    {
        $spatieDocumentAttributes['uuid'] = $this->uuid();

        $this->recordThat(new SpatieDocumentCreated($spatieDocumentAttributes));

        return $this;
    }

    public function editSpatieDocument(string $newContent, int $previousVersion, int $editorId): self
    {
        $this->recordThat(new SpatieDocumentEdited($this->uuid(), $newContent, $previousVersion, $editorId));

        // let's create a snapshot after every 5 edits for educational purposes
        if (($previousVersion + 1) % 5 === 0) {
            $this->snapshot();
        }

        return $this;
    }

    public function extendExpiration(string $newExpiresAt): self
    {
        $this->recordThat(new SpatieDocumentExpirationExtended($this->uuid(), $newExpiresAt));

        return $this;
    }

    public function lockSpatieDocument(): self
    {
        $this->recordThat(new SpatieDocumentLocked($this->uuid()));

        $this->snapshot();

        return $this;
    }
}
