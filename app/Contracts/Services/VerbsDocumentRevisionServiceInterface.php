<?php

namespace App\Contracts\Services;

use App\Data\VerbsDocumentRevisionCreationData;
use App\Models\VerbsDocumentRevision;

interface VerbsDocumentRevisionServiceInterface
{
    public function createVerbsDocumentRevision(VerbsDocumentRevisionCreationData $data): VerbsDocumentRevision;

    public function getMaxVersionVerbsDocumentRevisionByVerbsDocumentId(string $verbs_document_id): int;

    public function getUniqueEditorCountByVerbsDocumentId(string $verbs_document_id): int;
}
