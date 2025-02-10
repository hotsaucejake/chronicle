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
    public string $document_id;

    // The initial content for the document.
    public string $initial_content;

    public function __construct(string $document_id)
    {
        $this->document_id = $document_id;
        $this->initial_content = config('chronicle.initial_document_text');
    }

    public function validate(DocumentState $state): void {}

    public function apply(DocumentState $state): void
    {
        $state->content = $this->initial_content;
        $state->is_locked = false;
        $state->expires_at = now()->addHours(config('chronicle.document_expiration'));
    }

    public function handle(DocumentServiceInterface $documentService): void
    {
        $creationData = new DocumentCreationData($this->initial_content);

        $documentService->createDocument($creationData);
    }
}
