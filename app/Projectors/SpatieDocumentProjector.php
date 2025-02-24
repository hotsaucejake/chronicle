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

        if (is_null($document->first_edit_user_id)) {
            $document->update(['first_edit_user_id' => $event->editor_id]);
        }

        $document->update([
            'content' => $event->new_content,
            'version' => $event->previous_version + 1,
            'last_edit_user_id' => $event->editor_id,
            'edit_count' => $document->edit_count + 1,
            'last_edited_at' => now(),
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
