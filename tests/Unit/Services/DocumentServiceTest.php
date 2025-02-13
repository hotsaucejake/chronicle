<?php

use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentCreationData;
use App\Data\DocumentEditData;
use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\User;

beforeEach(function () {
    $this->documentService = app(DocumentServiceInterface::class);
});

it('creates an empty document', function () {
    // Create a DocumentCreationData instance with null content.
    $creationData = new DocumentCreationData(null);
    $document = $this->documentService->createDocument($creationData);

    expect($document->content)->toBeNull();
});

it('updates a document and creates a new revision', function () {
    // Create an initial document.
    $document = Document::factory()->create([
        'content' => 'Original Content',
        'edit_count' => 0,
    ]);

    $user = User::factory()->create();

    // Prepare a DocumentEditData instance.
    // Since we’re not simulating authentication here, pass editor_id explicitly.
    $editData = new DocumentEditData(
        document_id: $document->id,
        new_content: 'Updated Content',
        previous_version: 1,
        editor_id: $user->id,
    );

    $updatedDocument = $this->documentService->updateDocument($editData);

    expect($updatedDocument->content)->toEqual('Updated Content')
        ->and($updatedDocument->edit_count)->toEqual(1);

    // Verify that a revision was created with version = 1.
    $revisionVersion = DocumentRevision::where('document_id', $document->id)->max('version');
    expect($revisionVersion)->toEqual(1);
});
