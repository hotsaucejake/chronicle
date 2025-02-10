<?php

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Data\DocumentRevisionCreationData;
use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->revisionRepository = app(DocumentRevisionRepositoryInterface::class);
    $this->revisionService = app(DocumentRevisionServiceInterface::class);
});

it('creates a document revision', function () {
    $document = Document::factory()->create();
    $user = User::factory()->create();

    $data = new DocumentRevisionCreationData(
        document_id: $document->id,
        version: 1,
        content: 'Revision content',
        edited_by_user_id: $user->id,
        edited_at: CarbonImmutable::now()
    );

    $revision = $this->revisionService->createDocumentRevision($data);

    expect($revision)->toBeInstanceOf(DocumentRevision::class)
        ->and($revision->version)->toEqual(1)
        ->and($revision->content)->toEqual('Revision content');
});

it('returns the max version for a document', function () {
    $document = Document::factory()->create();
    $user = User::factory()->create();

    // Create two revisions with increasing version numbers.
    DocumentRevision::factory()->create([
        'document_id' => $document->id,
        'version' => 1,
        'content' => 'Revision 1',
        'edited_by_user_id' => $user->id,
        'edited_at' => now(),
    ]);

    DocumentRevision::factory()->create([
        'document_id' => $document->id,
        'version' => 2,
        'content' => 'Revision 2',
        'edited_by_user_id' => $user->id,
        'edited_at' => now(),
    ]);

    $maxVersion = $this->revisionService->getMaxVersionDocumentRevisionByDocumentId($document->id);

    expect($maxVersion)->toEqual(2);
});
