<?php

use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Data\VerbsDocumentEditData;
use App\Events\Document\Verbs\VerbsDocumentEdited;
use App\Models\VerbsDocument;
use App\Models\User;
use App\States\VerbsDocumentState;

// Test that apply() updates the state.
it('applies the VerbsDocumentEdited event to state', function () {
    // Create a fresh VerbsDocumentState with initial values.
    $state = new VerbsDocumentState;
    $state->content = 'Old Content';
    $state->version = 1;

    // Create an event with new content and previous version.
    $document = VerbsDocument::factory()->create();
    $event = new VerbsDocumentEdited($document->id, 'New Content', 1);

    // Call apply() on the event.
    $event->apply($state);

    // Assert that the content is updated and version incremented.
    expect($state->content)->toEqual('New Content')
        ->and($state->version)->toEqual(2);
});

// Test that handle() calls updateDocument() on the VerbsDocumentService.
it('calls updateDocument on VerbsDocumentService in handle', function () {
    $document = VerbsDocument::factory()->create();
    $editor = User::factory()->create();
    $this->actingAs($editor);
    $newContent = 'New Content';
    $previousVersion = 1;

    $event = new VerbsDocumentEdited((string) $document->id, $newContent, $previousVersion);

    // Create a fake VerbsDocumentServiceInterface.
    $fakeService = mock(VerbsDocumentServiceInterface::class);
    $fakeService->shouldReceive('updateVerbsDocument')
        ->once()
        ->withArgs(function (VerbsDocumentEditData $data) use ($document, $newContent, $previousVersion, $editor) {
            return $data->verbs_document_id === (string) $document->id
                && $data->new_content === $newContent
                && $data->previous_version === $previousVersion
                && $data->editor_id === (string) $editor->id;
        })
        // We don't care about the return value here; just return a dummy value.
        ->andReturns();

    $event->handle($fakeService);
});
