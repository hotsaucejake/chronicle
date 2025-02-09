<?php

namespace App\Services;

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Data\DocumentRevisionCreationData;
use App\Models\DocumentRevision;
use Illuminate\Support\Facades\DB;

class DocumentRevisionService implements DocumentRevisionServiceInterface
{
    public function __construct(protected DocumentRevisionRepositoryInterface $documentRevisionRepository) {}

    public function createDocumentRevision(DocumentRevisionCreationData $data): DocumentRevision
    {
        return DB::transaction(function () use ($data) {
            return $this->documentRevisionRepository->create($data->toArray());
        });
    }

    public function getMaxVersionDocumentRevisionByDocumentId(int $documentId): int
    {
        return $this->documentRevisionRepository->getMaxVersionForDocument($documentId);
    }
}
