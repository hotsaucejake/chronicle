<?php

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Models\VerbsDocument;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->documentRepository = app(DocumentRepositoryInterface::class);
});

it('can create a document', function () {

    $data = [
        'content' => 'Test document content',
        'is_locked' => false,
        'expires_at' => Carbon::now()->addHour(),
    ];

    $document = $this->documentRepository->create($data);

    expect($document)->toBeInstanceOf(VerbsDocument::class)
        ->and($document->content)->toEqual('Test document content')
        ->and($document->is_locked)->toBeFalse();
});

it('can find a document', function () {
    $document = VerbsDocument::factory()->create();

    $found = $this->documentRepository->find($document->id);

    expect((string) $found->id)->toEqual((string) $document->id);
});

it('can update a document', function () {
    $document = VerbsDocument::factory()->create([
        'content' => 'Old content',
    ]);

    $updated = $this->documentRepository->update($document, ['content' => 'New content']);

    expect($updated->content)->toEqual('New content');
});
