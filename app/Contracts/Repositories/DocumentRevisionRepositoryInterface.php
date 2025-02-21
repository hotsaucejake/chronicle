<?php

namespace App\Contracts\Repositories;

use App\Models\VerbsDocumentRevision;

interface DocumentRevisionRepositoryInterface
{
    public function create(array $data): VerbsDocumentRevision;

    public function getMaxVersionForVerbsDocument(string $verbs_document_id): int;

    public function getUniqueEditorCountByVerbsDocumentId(string $verbs_document_id): int;
}
