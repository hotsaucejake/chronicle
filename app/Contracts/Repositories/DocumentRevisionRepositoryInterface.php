<?php

namespace App\Contracts\Repositories;

use App\Models\DocumentRevision;

interface DocumentRevisionRepositoryInterface
{
    public function create(array $data): DocumentRevision;

    public function getMaxVersionForDocument(string $documentId): int;
}
