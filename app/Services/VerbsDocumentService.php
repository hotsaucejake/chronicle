<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Data\VerbsDocumentCreationData;
use App\Data\VerbsDocumentEditData;
use App\Data\VerbsDocumentRevisionCreationData;
use App\Events\Document\Verbs\VerbsDocumentCreated;
use App\Events\Document\Verbs\VerbsDocumentLocked;
use App\Models\VerbsDocument;
use Illuminate\Support\Facades\DB;

class VerbsDocumentService implements VerbsDocumentServiceInterface
{
    public function __construct(
        protected DocumentRepositoryInterface $documentRepository,
        protected VerbsDocumentRevisionServiceInterface $documentRevisionService,
    ) {}

    public function getVerbsDocumentById(string $verbs_document_id): VerbsDocument
    {
        return $this->documentRepository->find($verbs_document_id);
    }

    public function createVerbsDocument(VerbsDocumentCreationData $data): VerbsDocument
    {
        $payload = $data->toArray();

        return $this->documentRepository->create($payload);
    }

    public function updateVerbsDocument(VerbsDocumentEditData $data): VerbsDocument
    {
        return DB::transaction(function () use ($data) {
            $document = $this->documentRepository->find($data->verbs_document_id);
            $edit_count = $document->edit_count + 1;

            $updateData = [
                'content' => $data->new_content,
                'last_edit_user_id' => $data->editor_id,
                'edit_count' => $edit_count,
                'last_edited_at' => now(),
            ];

            // If this is the first edit, set first_edit_user_id.
            if (is_null($document->first_edit_user_id)) {
                $updateData['first_edit_user_id'] = $data->editor_id;
            }

            $newDocument = $this->documentRepository->update($document, $updateData);

            $version = $this->documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($data->verbs_document_id) + 1;

            $documentRevisionData = VerbsDocumentRevisionCreationData::from([
                'verbs_document_id' => $data->verbs_document_id,
                'version' => $version,
                'content' => $data->new_content,
                'edited_by_user_id' => $data->editor_id,
                'edited_at' => now(),
            ]);

            $this->documentRevisionService->createVerbsDocumentRevision($documentRevisionData);

            // Update unique_editor_count:
            // Query the revisions table for distinct editors for this document.
            $uniqueEditorsCount = $this->documentRevisionService->getUniqueEditorCountByVerbsDocumentId($data->verbs_document_id);

            // Update the document projection with the new count.
            return $this->documentRepository->update($newDocument, [
                'unique_editor_count' => $uniqueEditorsCount,
            ]);
        });
    }

    public function lockVerbsDocumentById(string $verbs_document_id): bool
    {
        return DB::transaction(function () use ($verbs_document_id) {
            $document = $this->documentRepository->find($verbs_document_id);

            if (!$document) {
                return false;
            }

            if ($document->is_locked) {
                return false;
            }

            $updated = $this->documentRepository->update($document, [
                'is_locked' => true,
            ]);

            return $updated->is_locked;
        });
    }

    public function lockOpenExpiredVerbsDocuments(): void
    {
        $this->documentRepository
            ->retrieveOpenExpiredDocuments()
            ->each(function (VerbsDocument $document) {
                if (
                    is_null($document->first_edit_user_id)
                    && is_null($document->last_edit_user_id)
                    && $document->unique_editor_count === 0
                    && $document->edit_count === 0
                    && is_null($document->last_edited_at)
                ) {
                    // if the document hasn't been edited then extend the expiration until it has
                    $this->documentRepository->update($document, [
                        'expires_at' => now()->addHours(config('chronicle.document_expiration', 1)),
                    ]);
                } else {
                    VerbsDocumentLocked::fire(verbs_document_id: $document->id);
                }
            });
    }

    public function createNewOpenVerbsDocument(): void
    {
        VerbsDocumentCreated::fire();
    }

    public function livingVerbsDocumentsCount(): int
    {
        return $this->documentRepository->livingDocumentsCount();
    }
}
