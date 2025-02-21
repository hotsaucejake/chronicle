<?php

namespace App\Events\Document\Verbs;

use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Data\VerbsDocumentEditData;
use App\Events\Document\DocumentEditedBroadcast;
use App\States\VerbsDocumentState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class VerbsDocumentEdited extends Event
{
    public function __construct(
        #[StateId(VerbsDocumentState::class)] public int $verbs_document_id,
        public string $new_content,
        public int $previous_version, // passed from the client for optimistic concurrency
        public int $editor_id,
    ) {}

    public function validate(VerbsDocumentState $state): void
    {
        // Optionally enforce optimistic concurrency.
        $this->assert($state->version === $this->previous_version, 'VerbsDocument has been updated since you loaded it.');
    }

    public function apply(VerbsDocumentState $state): void
    {
        $state->content = $this->new_content;
        $state->version++;
    }

    public function handle(VerbsDocumentServiceInterface $documentService): void
    {
        $edit_data = VerbsDocumentEditData::from([
            'verbs_document_id' => $this->verbs_document_id,
            'new_content' => $this->new_content,
            'previous_version' => $this->previous_version,
            'editor_id' => $this->editor_id,
        ]);

        $documentService->updateVerbsDocument($edit_data);

        event(new DocumentEditedBroadcast(
            verbs_document_id: $this->verbs_document_id,
            new_content: $this->new_content,
        ));
    }
}
