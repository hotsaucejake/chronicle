<?php

namespace App\Contracts\Services;

use App\Data\VerbsDocumentCreationData;
use App\Data\VerbsDocumentEditData;
use App\Models\VerbsDocument;

interface VerbsDocumentServiceInterface
{
    public function getVerbsDocumentById(string $verbs_document_id): VerbsDocument;

    public function createVerbsDocument(VerbsDocumentCreationData $data): VerbsDocument;

    public function updateVerbsDocument(VerbsDocumentEditData $data): VerbsDocument;

    public function lockVerbsDocumentById(string $verbs_document_id): bool;

    public function lockOpenExpiredVerbsDocuments(): void;

    public function createNewOpenVerbsDocument(): void;

    public function livingVerbsDocumentsCount(): int;
}
