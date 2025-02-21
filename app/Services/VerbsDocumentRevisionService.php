<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Data\VerbsDocumentRevisionCreationData;
use App\Models\VerbsDocumentRevision;
use Illuminate\Support\Facades\DB;
use Throwable;

class VerbsDocumentRevisionService implements VerbsDocumentRevisionServiceInterface
{
    public function __construct(protected DocumentRevisionRepositoryInterface $documentRevisionRepository) {}

    /**
     * @throws Throwable
     */
    public function createVerbsDocumentRevision(VerbsDocumentRevisionCreationData $data): VerbsDocumentRevision
    {
        return DB::transaction(function () use ($data) {
            return $this->documentRevisionRepository->create($data->toArray());
        });
    }

    public function getMaxVersionVerbsDocumentRevisionByVerbsDocumentId(string $verbs_document_id): int
    {
        return $this->documentRevisionRepository->getMaxVersionForVerbsDocument($verbs_document_id);
    }

    public function getUniqueEditorCountByVerbsDocumentId(string $verbs_document_id): int
    {
        return $this->documentRevisionRepository->getUniqueEditorCountByVerbsDocumentId($verbs_document_id);
    }
}
