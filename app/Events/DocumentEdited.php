<?php

namespace App\Events;

use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentEditData;
use App\States\DocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class DocumentEdited extends Event
{
    public function __construct(
        #[StateId(DocumentState::class)] public int $document_id,
        public string $new_content,
        public int $previous_version // passed from the client for optimistic concurrency
    ) {}

    public function validate(DocumentState $state): void
    {
        // Optionally enforce optimistic concurrency.
        $this->assert($state->version === $this->previous_version, 'Document has been updated since you loaded it.');
    }

    public function apply(DocumentState $state): void
    {
        $state->content = $this->new_content;
        $state->version++;
    }

    public function handle(DocumentServiceInterface $documentService): void
    {
        $edit_data = DocumentEditData::from([
            'document_id' => $this->document_id,
            'new_content' => $this->new_content,
            'previous_version' => $this->previous_version,
        ]);

        $documentService->updateDocument($edit_data);
    }
}
