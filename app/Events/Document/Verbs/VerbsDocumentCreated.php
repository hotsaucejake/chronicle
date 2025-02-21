<?php

namespace App\Events\Document\Verbs;

use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Data\VerbsDocumentCreationData;
use App\States\VerbsDocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class VerbsDocumentCreated extends Event
{
    #[StateId(VerbsDocumentState::class)]
    public ?int $verbs_document_id = null;

    public function validate(VerbsDocumentState $state): void {}

    public function apply(VerbsDocumentState $state): void
    {
        $state->content = config('chronicle.initial_document_text', 'New Content');
        $state->is_locked = false;
        $state->expires_at = now()->addHours(config('chronicle.document_expiration', 1));
    }

    public function handle(
        VerbsDocumentState            $state,
        VerbsDocumentServiceInterface $documentService,
    ): void {
        $documentService->createVerbsDocument(new VerbsDocumentCreationData(
            content: $state->content,
            is_locked: $state->is_locked,
            expires_at: $state->expires_at,
        ));
    }
}
