<?php

use App\Contracts\Repositories\DocumentRevisionRepositoryInterface;
use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->documentRevisionRepository = app(DocumentRevisionRepositoryInterface::class);
});

it('can create a document revision', function () {
    // Create a document so we have a valid foreign key.
    $document = Document::factory()->create();
    $user = User::factory()->create();

    $data = [
        'document_id' => $document->id,
        'version' => 1,
        'content' => 'Revision content',
        'edited_by_user_id' => $user->id,
        'edited_at' => Carbon::now(),
    ];

    $revision = $this->documentRevisionRepository->create($data);

    expect($revision)->toBeInstanceOf(DocumentRevision::class)
        ->and($revision->version)->toEqual(1)
        ->and($revision->content)->toEqual('Revision content');
});

it('returns the max version for a document', function () {
    // Create a document.
    $document = Document::factory()->create();
    $user = User::factory()->create();

    // Create two revisions with increasing version numbers.
    DocumentRevision::factory()
        ->create([
            'document_id' => $document->id,
            'edited_by_user_id' => $user->id,
            'version' => 1,
            'content' => 'Revision 1',
            'edited_at' => Carbon::now(),
        ]);

    DocumentRevision::factory()
        ->create([
            'document_id' => $document->id,
            'edited_by_user_id' => $user->id,
            'version' => 2,
            'content' => 'Revision 2',
            'edited_at' => Carbon::now(),
        ]);

    $maxVersion = $this->documentRevisionRepository->getMaxVersionForDocument($document->id);

    expect($maxVersion)->toEqual(2);
});
