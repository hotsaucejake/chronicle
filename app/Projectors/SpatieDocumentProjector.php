<?php

namespace App\Projectors;

use App\Events\Document\Spatie\SpatieDocumentCreated;
use App\Events\Document\Spatie\SpatieDocumentEdited;
use App\Events\Document\Spatie\SpatieDocumentLocked;
use App\Projections\SpatieDocumentProjection;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class SpatieDocumentProjector extends Projector
{
    public function onSpatieDocumentCreated(SpatieDocumentCreated $event): void
    {
        SpatieDocumentProjection::create($event->spatieDocumentAttributes);
    }

    public function onSpatieDocumentEdited(SpatieDocumentEdited $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid);
        $document->update([
            'content' => $event->new_content,
            'version' => $event->previous_version + 1,
            'editor_id' => $event->editor_id,
        ]);
    }

    public function onSpatieDocumentLocked(SpatieDocumentLocked $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid);
        $document->update([
            'is_locked' => true,
        ]);
    }
}
