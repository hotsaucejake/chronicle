<?php

namespace App\Contracts\Services;

use App\Data\DocumentCreationData;
use App\Data\DocumentEditData;
use App\Models\Document;
use App\States\DocumentState;

interface DocumentServiceInterface
{
    public function createDocument(DocumentCreationData $data): Document;

    public function updateDocument(DocumentEditData $data): Document;

    public function lockDocumentById(string $document_id): bool;

    public function lockDocument(Document $document): bool;

    public function lockOpenExpiredDocuments(): void;

    public function createNewOpenDocument(): void;

    public function livingDocumentsCount(): int;
}
