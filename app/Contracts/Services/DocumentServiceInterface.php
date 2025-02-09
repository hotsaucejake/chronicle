<?php

namespace App\Contracts\Services;

use App\Data\DocumentCreationData;
use App\Data\DocumentEditData;
use App\Models\Document;

interface DocumentServiceInterface
{
    public function createDocument(DocumentCreationData $data): Document;

    public function updateDocument(DocumentEditData $data): Document;
}
