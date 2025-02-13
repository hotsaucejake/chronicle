<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Models\DocumentRevision;

class EloquentDocumentRevisionRepository implements DocumentRevisionRepositoryInterface
{
    public function create(array $data): DocumentRevision
    {
        return DocumentRevision::create($data);
    }

    public function getMaxVersionForDocument(string $documentId): int
    {
        return DocumentRevision::where('document_id', $documentId)->max('version') ?? 0;
    }

    public function getUniqueEditorCountByDocumentId(string $documentId): int
    {
        return DocumentRevision::where('document_id', $documentId)
            ->distinct()
            ->count('edited_by_user_id');
    }
}
