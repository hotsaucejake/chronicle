<?php

namespace App\Projectors;

use App\Events\Document\Spatie\SpatieDocumentCreated;
use App\Events\Document\Spatie\SpatieDocumentEdited;
use App\Events\Document\Spatie\SpatieDocumentLocked;
use App\Projections\SpatieDocumentProjection;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class SpatieDocumentProjector extends Projector
{
    public function onSpatieDocumentCreated(SpatieDocumentCreated $event): void
    {
        // Ensure UUID is set
        if (empty($event->spatieDocumentAttributes['uuid'])) {
            $event->spatieDocumentAttributes['uuid'] = Uuid::uuid4()->toString();
        }
        $projection = SpatieDocumentProjection::createWithAttributes($event->spatieDocumentAttributes);
        $projection->save();
    }

    public function onSpatieDocumentEdited(SpatieDocumentEdited $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid)->writeable();
        $document->update([
            'content' => $event->new_content,
            'version' => $event->previous_version + 1,
            'editor_id' => $event->editor_id,
        ]);
    }

    public function onSpatieDocumentLocked(SpatieDocumentLocked $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid)->writeable();
        $document->update([
            'is_locked' => true,
        ]);
    }
}
