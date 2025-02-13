<?php

namespace App\Contracts\Services;

use App\Data\DocumentRevisionCreationData;
use App\Models\DocumentRevision;

interface DocumentRevisionServiceInterface
{
    public function createDocumentRevision(DocumentRevisionCreationData $data): DocumentRevision;

    public function getMaxVersionDocumentRevisionByDocumentId(string $documentId): int;

    public function getUniqueEditorCountByDocumentId(string $documentId): int;
}
