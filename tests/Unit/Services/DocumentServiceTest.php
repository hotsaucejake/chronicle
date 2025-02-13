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

it('creates a document', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $creationData = new DocumentCreationData('Initial Content');

    $document = $this->documentService->createDocument($creationData);

    expect($document->content)->toEqual('Initial Content')
        ->and($document->is_locked)->toBeFalse();
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
        ->and($updatedDocument->edit_count)->toEqual(1)
        ->and($updatedDocument->first_edit_user_id)->toEqual($user->id)
        ->and($updatedDocument->last_edit_user_id)->toEqual($user->id)
        ->and($updatedDocument->unique_editor_count)->toEqual(1);

    // Verify that a revision was created with version = 1.
    $revisionVersion = DocumentRevision::where('document_id', $document->id)->max('version');
    expect($revisionVersion)->toEqual(1);
});

it('locks a document by id', function () {
    $document = Document::factory()->create([
        'is_locked' => false,
    ]);

    $isLocked = $this->documentService->lockDocumentById($document->id);

    expect($isLocked)->toBeTrue();
});

it('does not lock a document that is already locked', function () {
    $document = Document::factory()->create([
        'is_locked' => true,
    ]);

    $isLocked = $this->documentService->lockDocumentById($document->id);

    expect($isLocked)->toBeFalse();
});

it('does not lock a document that does not exist', function () {
    $isLocked = $this->documentService->lockDocumentById(0);

    expect($isLocked)->toBeFalse();
});

it('locks open expired documents', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $document = Document::factory()->create([
        'is_locked' => false,
        'expires_at' => now()->subDays(2),
    ]);

    $this->documentService->lockOpenExpiredDocuments();
    Verbs::commit();

    $document->refresh();

    expect($document->is_locked)->toBeTrue();
});

it('does not lock documents that are not expired', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $document = Document::factory()->create([
        'is_locked' => false,
        'expires_at' => now()->addDays(2),
    ]);

    $this->documentService->lockOpenExpiredDocuments();
    Verbs::commit();

    $document->refresh();

    expect($document->is_locked)->toBeFalse();
});

it('creates new open document', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $this->documentService->createNewOpenDocument();
    Verbs::commit();

    $document = Document::where('is_locked', false)
        ->latest()
        ->first();

    expect($document->content)->toEqual('Default Content');
});

it('retrieves living documents count', function () {
    $document = Document::factory()->create([
        'is_locked' => false,
    ]);

    $livingDocumentsCount = $this->documentService->livingDocumentsCount();

    expect($livingDocumentsCount)->toEqual(1);
});
