<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentCreationData;
use App\Data\DocumentEditData;
use App\Data\DocumentRevisionCreationData;
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
        return DB::transaction(function () use ($data) {
            $payload = $data->toArray();

            if (empty($payload['content'])) {
                $payload['content'] = config('chronicle.initial_document_text');
            }

            $payload['is_locked'] = false;
            $payload['expires_at'] = now()->addHours(config('chronicle.document_expiration'));

            return $this->documentRepository->create($payload);
        });
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

            return $newDocument;
        });
    }

    public function lockDocument(string $document_id): bool
    {
        return DB::transaction(function () use ($document_id) {
            $document = $this->documentRepository->find($document_id);

            if ($document->is_locked) {
                return false;
            }

            return $this->documentRepository->update($document, [
                'is_locked' => true,
            ]);
        });
    }
}
