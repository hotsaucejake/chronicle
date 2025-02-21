<?php

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->documentRevisionRepository = app(DocumentRevisionRepositoryInterface::class);
});

it('can create a document revision', function () {
    // Create a document so we have a valid foreign key.
    $document = VerbsDocument::factory()->create();
    $user = User::factory()->create();

    $data = [
        'verbs_document_id' => $document->id,
        'version' => 1,
        'content' => 'Revision content',
        'edited_by_user_id' => $user->id,
        'edited_at' => Carbon::now(),
    ];

    $revision = $this->documentRevisionRepository->create($data);

    expect($revision)->toBeInstanceOf(VerbsDocumentRevision::class)
        ->and($revision->version)->toEqual(1)
        ->and($revision->content)->toEqual('Revision content');
});

it('returns the max version for a document', function () {
    // Create a document.
    $document = VerbsDocument::factory()->create();
    $user = User::factory()->create();

    // Create two revisions with increasing version numbers.
    VerbsDocumentRevision::factory()
        ->create([
            'verbs_document_id' => $document->id,
            'edited_by_user_id' => $user->id,
            'version' => 1,
            'content' => 'Revision 1',
            'edited_at' => Carbon::now(),
        ]);

    VerbsDocumentRevision::factory()
        ->create([
            'verbs_document_id' => $document->id,
            'edited_by_user_id' => $user->id,
            'version' => 2,
            'content' => 'Revision 2',
            'edited_at' => Carbon::now(),
        ]);

    $maxVersion = $this->documentRevisionRepository->getMaxVersionForVerbsDocument($document->id);

    expect($maxVersion)->toEqual(2);
});
