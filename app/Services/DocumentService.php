<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentCreationData;
use App\Data\DocumentEditData;
use App\Data\DocumentRevisionCreationData;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentLocked;
use App\Models\Document;
use Illuminate\Support\Facades\DB;

class DocumentService implements DocumentServiceInterface
{
    public function __construct(
        protected DocumentRepositoryInterface $documentRepository,
        protected DocumentRevisionServiceInterface $documentRevisionService,
    ) {}

    public function createDocument(DocumentCreationData $data): Document
    {
        $payload = $data->toArray();

        return $this->documentRepository->create($payload);
    }

    public function updateDocument(DocumentEditData $data): Document
    {
        return DB::transaction(function () use ($data) {
            $document = $this->documentRepository->find($data->document_id);
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

            $version = $this->documentRevisionService->getMaxVersionDocumentRevisionByDocumentId($data->document_id) + 1;

            $documentRevisionData = DocumentRevisionCreationData::from([
                'document_id' => $data->document_id,
                'version' => $version,
                'content' => $data->new_content,
                'edited_by_user_id' => $data->editor_id,
                'edited_at' => now(),
            ]);

            $this->documentRevisionService->createDocumentRevision($documentRevisionData);

            // Update unique_editor_count:
            // Query the revisions table for distinct editors for this document.
            $uniqueEditorsCount = $this->documentRevisionService->getUniqueEditorCountByDocumentId($data->document_id);

            // Update the document projection with the new count.
            return $this->documentRepository->update($newDocument, [
                'unique_editor_count' => $uniqueEditorsCount,
            ]);
        });
    }

    public function lockDocumentById(string $document_id): bool
    {
        return DB::transaction(function () use ($document_id) {
            $document = $this->documentRepository->find($document_id);

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

    public function lockOpenExpiredDocuments(): void
    {
        $this->documentRepository
            ->retrieveOpenExpiredDocuments()
            ->each(function (Document $document) {
                DocumentLocked::fire(document_id: $document->id);
            });
    }

    public function createNewOpenDocument(): void
    {
        DocumentCreated::fire();
    }

    public function livingDocumentsCount(): int
    {
        return $this->documentRepository->livingDocumentsCount();
    }
}
