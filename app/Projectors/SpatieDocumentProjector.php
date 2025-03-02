<?php

namespace App\Projectors;

use App\Events\Document\Spatie\SpatieDocumentCreated;
use App\Events\Document\Spatie\SpatieDocumentEdited;
use App\Events\Document\Spatie\SpatieDocumentExpirationExtended;
use App\Events\Document\Spatie\SpatieDocumentLocked;
use App\Projections\SpatieDocumentProjection;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class SpatieDocumentProjector extends Projector
{
    public function onSpatieDocumentCreated(SpatieDocumentCreated $event): void
    {
        (new SpatieDocumentProjection($event->spatieDocumentAttributes))->writeable()->save();
    }

    public function onSpatieDocumentEdited(SpatieDocumentEdited $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid)->writeable();

        if (is_null($document->first_edit_user_id)) {
            $document->update(['first_edit_user_id' => $event->editor_id]);
        }

        // Decode the current list of editor IDs (or initialize as empty array)
        $editorIds = $document->editor_ids ? json_decode($document->editor_ids, true) : [];

        // Add the current editor if not already present.
        if (!in_array($event->editor_id, $editorIds)) {
            $editorIds[] = $event->editor_id;
        }

        $document->update([
            'content' => $event->new_content,
            'version' => $event->previous_version + 1,
            'last_edit_user_id' => $event->editor_id,
            'edit_count' => $document->edit_count + 1,
            'last_edited_at' => now(),
            'unique_editor_count' => count($editorIds),
            'editor_ids' => json_encode($editorIds),
        ]);
    }

    public function onSpatieDocumentExpirationExtended(SpatieDocumentExpirationExtended $event): void
    {
        $document = SpatieDocumentProjection::uuid($event->uuid)->writeable();
        $document->update([
            'expires_at' => $event->new_expires_at,
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
