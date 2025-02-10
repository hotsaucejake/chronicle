<?php

namespace App\Contracts\Services;

use App\Data\DocumentRevisionCreationData;
use App\Models\DocumentRevision;
use Glhd\Bits\Snowflake;

interface DocumentRevisionServiceInterface
{
    public function createDocumentRevision(DocumentRevisionCreationData $data): DocumentRevision;

    public function getMaxVersionDocumentRevisionByDocumentId(Snowflake $documentId): int;
}
