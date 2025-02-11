<?php

namespace App\Events;

use App\Contracts\Services\DocumentServiceInterface;
use App\States\DocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class DocumentLocked extends Event
{
    #[StateId(DocumentState::class)]
    public string $document_id;

    public function __construct(string $document_id)
    {
        $this->document_id = $document_id;
    }

    public function validate(DocumentState $state): void
    {
        // Ensure that the document is not already locked.
        $this->assert(!$state->is_locked, 'Document is already locked.');
    }

    public function apply(DocumentState $state): void
    {
        $state->is_locked = true;
    }

    public function handle(DocumentServiceInterface $documentService): void
    {
        $documentService->lockDocumentById($this->document_id);
    }
}
