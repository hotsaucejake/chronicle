<?php

use App\Contracts\Services\DocumentServiceInterface;
use App\Data\DocumentEditData;
use App\Events\Document\DocumentEdited;
use App\Models\Document;
use App\Models\User;
use App\States\DocumentState;

// Test that apply() updates the state.
it('applies the DocumentEdited event to state', function () {
    // Create a fresh DocumentState with initial values.
    $state = new DocumentState;
    $state->content = 'Old Content';
    $state->version = 1;

    // Create an event with new content and previous version.
    $document = Document::factory()->create();
    $event = new DocumentEdited($document->id, 'New Content', 1);

    // Call apply() on the event.
    $event->apply($state);

    // Assert that the content is updated and version incremented.
    expect($state->content)->toEqual('New Content')
        ->and($state->version)->toEqual(2);
});

// Test that handle() calls updateDocument() on the DocumentService.
it('calls updateDocument on DocumentService in handle', function () {
    $document = Document::factory()->create();
    $editor = User::factory()->create();
    $this->actingAs($editor);
    $newContent = 'New Content';
    $previousVersion = 1;

    $event = new DocumentEdited((string) $document->id, $newContent, $previousVersion);

    // Create a fake DocumentServiceInterface.
    $fakeService = mock(DocumentServiceInterface::class);
    $fakeService->shouldReceive('updateDocument')
        ->once()
        ->withArgs(function (DocumentEditData $data) use ($document, $newContent, $previousVersion, $editor) {
            return $data->document_id === (string) $document->id
                && $data->new_content === $newContent
                && $data->previous_version === $previousVersion
                && $data->editor_id === (string) $editor->id;
        })
        // We don't care about the return value here; just return a dummy value.
        ->andReturns();

    $event->handle($fakeService);
});
