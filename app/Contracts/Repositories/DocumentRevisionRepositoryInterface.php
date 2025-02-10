<?php

namespace App\Contracts\Repositories;

use App\Models\DocumentRevision;
use Glhd\Bits\Snowflake;

interface DocumentRevisionRepositoryInterface
{
    public function create(array $data): DocumentRevision;

    public function getMaxVersionForDocument(Snowflake $documentId): int;
}
