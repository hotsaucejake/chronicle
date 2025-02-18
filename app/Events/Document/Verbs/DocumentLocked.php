<?php

namespace App\Events\Document\Verbs;

use App\Contracts\Services\DocumentServiceInterface;
use App\States\DocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class DocumentLocked extends Event
{
    public function __construct(
        #[StateId(DocumentState::class)] public int $document_id
    ) {}

    /**
     * @throws \Throwable
     */
    public function validate(DocumentState $state): void
    {
        // Ensure that the document is not already locked.
        $this->assert(!$state->is_locked, 'Document is already locked.');
    }

    public function apply(DocumentState $state): void
    {
        $state->is_locked = true;
    }

    public function handle(DocumentState $state, DocumentServiceInterface $documentService): void
    {
        $documentService->lockDocumentById($this->document_id);
    }
}
