<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Models\VerbsDocumentRevision;

class EloquentDocumentRevisionRepository implements DocumentRevisionRepositoryInterface
{
    public function create(array $data): VerbsDocumentRevision
    {
        return VerbsDocumentRevision::create($data);
    }

    public function getMaxVersionForVerbsDocument(string $verbs_document_id): int
    {
        return VerbsDocumentRevision::where('verbs_document_id', $verbs_document_id)->max('version') ?? 0;
    }

    public function getUniqueEditorCountByVerbsDocumentId(string $verbs_document_id): int
    {
        return VerbsDocumentRevision::where('verbs_document_id', $verbs_document_id)
            ->distinct()
            ->count('edited_by_user_id');
    }
}
