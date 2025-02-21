<?php

use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Data\VerbsDocumentCreationData;
use App\Data\VerbsDocumentEditData;
use App\Models\User;
use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;

beforeEach(function () {
    $this->documentService = app(VerbsDocumentServiceInterface::class);
});

it('creates an empty document', function () {
    // Create a VerbsDocumentCreationData instance with null content.
    $creationData = new VerbsDocumentCreationData(null);
    $document = $this->documentService->createVerbsDocument($creationData);

    expect($document->content)->toBeNull();
});

it('creates a document', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $creationData = new VerbsDocumentCreationData('Initial Content');

    $document = $this->documentService->createVerbsDocument($creationData);

    expect($document->content)->toEqual('Initial Content')
        ->and($document->is_locked)->toBeFalse();
});

it('updates a document and creates a new revision', function () {
    // Create an initial document.
    $document = VerbsDocument::factory()->create([
        'content' => 'Original Content',
        'edit_count' => 0,
    ]);

    $user = User::factory()->create();

    // Prepare a VerbsDocumentEditData instance.
    // Since we’re not simulating authentication here, pass editor_id explicitly.
    $editData = new VerbsDocumentEditData(
        verbs_document_id: $document->id,
        new_content: 'Updated Content',
        previous_version: 1,
        editor_id: $user->id,
    );

    $updatedDocument = $this->documentService->updateVerbsDocument($editData);

    expect($updatedDocument->content)->toEqual('Updated Content')
        ->and($updatedDocument->edit_count)->toEqual(1)
        ->and($updatedDocument->first_edit_user_id)->toEqual($user->id)
        ->and($updatedDocument->last_edit_user_id)->toEqual($user->id)
        ->and($updatedDocument->unique_editor_count)->toEqual(1);

    // Verify that a revision was created with version = 1.
    $revisionVersion = VerbsDocumentRevision::where('verbs_document_id', $document->id)->max('version');
    expect($revisionVersion)->toEqual(1);
});

it('locks a document by id', function () {
    $document = VerbsDocument::factory()->create([
        'is_locked' => false,
    ]);

    $isLocked = $this->documentService->lockVerbsDocumentById($document->id);

    expect($isLocked)->toBeTrue();
});

it('does not lock a document that is already locked', function () {
    $document = VerbsDocument::factory()->create([
        'is_locked' => true,
    ]);

    $isLocked = $this->documentService->lockVerbsDocumentById($document->id);

    expect($isLocked)->toBeFalse();
});

it('does not lock a document that does not exist', function () {
    $isLocked = $this->documentService->lockVerbsDocumentById(0);

    expect($isLocked)->toBeFalse();
});

it('locks open expired documents', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $document = VerbsDocument::factory()->create([
        'is_locked' => false,
        'expires_at' => now()->subDays(2),
        'edit_count' => 1,
    ]);

    $this->documentService->lockOpenExpiredVerbsDocuments();
    Verbs::commit();

    $document->refresh();

    expect($document->is_locked)->toBeTrue();
});

it('does not lock documents that are not expired', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $document = VerbsDocument::factory()->create([
        'is_locked' => false,
        'expires_at' => now()->addDays(2),
    ]);

    $this->documentService->lockOpenExpiredVerbsDocuments();
    Verbs::commit();

    $document->refresh();

    expect($document->is_locked)->toBeFalse();
});

it('creates new open document', function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);

    $this->documentService->createNewOpenVerbsDocument();
    Verbs::commit();

    $document = VerbsDocument::where('is_locked', false)
        ->latest()
        ->first();

    expect($document->content)->toEqual('Default Content');
});

it('retrieves living documents count', function () {
    $document = VerbsDocument::factory()->create([
        'is_locked' => false,
    ]);

    $livingDocumentsCount = $this->documentService->livingVerbsDocumentsCount();

    expect($livingDocumentsCount)->toEqual(1);
});
