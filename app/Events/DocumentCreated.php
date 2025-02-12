<?php

namespace App\Events;

use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentCreationData;
use App\States\DocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class DocumentCreated extends Event
{
    #[StateId(DocumentState::class)]
    public ?int $document_id = null;

    public function validate(DocumentState $state): void {}

    public function apply(DocumentState $state): void
    {
        $state->content = config('chronicle.initial_document_text', 'New Content');
        $state->is_locked = false;
        $state->expires_at = now()->addHours(config('chronicle.document_expiration', 1));
    }

    public function handle(
        DocumentState $state,
        DocumentServiceInterface $documentService,
    ): void {
        $documentService->createDocument(new DocumentCreationData(
            content: $state->content,
            is_locked: $state->is_locked,
            expires_at: $state->expires_at,
        ));
    }
}
