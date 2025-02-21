<?php

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Data\VerbsDocumentRevisionCreationData;
use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->revisionRepository = app(DocumentRevisionRepositoryInterface::class);
    $this->revisionService = app(VerbsDocumentRevisionServiceInterface::class);
});

it('creates a document revision', function () {
    $document = VerbsDocument::factory()->create();
    $user = User::factory()->create();

    $data = new VerbsDocumentRevisionCreationData(
        verbs_document_id: $document->id,
        version: 1,
        content: 'Revision content',
        edited_by_user_id: $user->id,
        edited_at: CarbonImmutable::now()
    );

    $revision = $this->revisionService->createVerbsDocumentRevision($data);

    expect($revision)->toBeInstanceOf(VerbsDocumentRevision::class)
        ->and($revision->version)->toEqual(1)
        ->and($revision->content)->toEqual('Revision content');
});

it('returns the max version for a document', function () {
    $document = VerbsDocument::factory()->create();
    $user = User::factory()->create();

    // Create two revisions with increasing version numbers.
    VerbsDocumentRevision::factory()->create([
        'verbs_document_id' => $document->id,
        'version' => 1,
        'content' => 'Revision 1',
        'edited_by_user_id' => $user->id,
        'edited_at' => now(),
    ]);

    VerbsDocumentRevision::factory()->create([
        'verbs_document_id' => $document->id,
        'version' => 2,
        'content' => 'Revision 2',
        'edited_by_user_id' => $user->id,
        'edited_at' => now(),
    ]);

    $maxVersion = $this->revisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id);

    expect($maxVersion)->toEqual(2);
});
