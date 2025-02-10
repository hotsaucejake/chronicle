<?php

use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentCreationData;
use App\Events\DocumentCreated;
use App\Models\Document;
use App\States\DocumentState;
use Carbon\Carbon;

beforeEach(function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);
});

it('applies the DocumentCreated event to state', function () {
    // Create a fresh DocumentState.
    $state = new DocumentState;
    $document = Document::factory()->create();

    $event = new DocumentCreated($document->id);

    // Call apply() on the event.
    $event->apply($state);

    // Assert that the state was updated.
    expect($state->content)->toEqual('Default Content')
        ->and($state->is_locked)->toBeFalse()
        ->and($state->expires_at->diffInMinutes(Carbon::now()))->toBeLessThanOrEqual(1);
});

// Test that handle() calls the createDocument() method on the DocumentService.
it('calls createDocument on DocumentService in handle', function () {
    $document = Document::factory()->create();

    // Create a fake DocumentServiceInterface using Pest's mock helper.
    $fakeService = mock(DocumentServiceInterface::class);
    $fakeService->shouldReceive('createDocument')
        ->once()
        ->withArgs(function (DocumentCreationData $data) {
            return $data->content === 'Default Content';
        })
        ->andReturns();

    $event = new DocumentCreated($document->id);
    $event->handle($fakeService);
});
