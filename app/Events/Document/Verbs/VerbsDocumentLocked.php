<?php

namespace App\Events\Document\Verbs;

use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\States\VerbsDocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class VerbsDocumentLocked extends Event
{
    public function __construct(
        #[StateId(VerbsDocumentState::class)] public int $verbs_document_id
    ) {}

    /**
     * @throws \Throwable
     */
    public function validate(VerbsDocumentState $state): void
    {
        // Ensure that the document is not already locked.
        $this->assert(!$state->is_locked, 'VerbsDocument is already locked.');
    }

    public function apply(VerbsDocumentState $state): void
    {
        $state->is_locked = true;
    }

    public function handle(VerbsDocumentState $state, VerbsDocumentServiceInterface $documentService): void
    {
        $documentService->lockVerbsDocumentById($this->verbs_document_id);
    }
}
