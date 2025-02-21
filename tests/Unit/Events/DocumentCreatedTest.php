<?php

use App\Events\Document\Verbs\VerbsDocumentCreated;
use App\States\VerbsDocumentState;
use Carbon\Carbon;

beforeEach(function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);
});

it('applies the VerbsDocumentCreated event to state', function () {
    // Create a fresh VerbsDocumentState.
    $state = new VerbsDocumentState;

    $event = new VerbsDocumentCreated;

    // Call apply() on the event.
    $event->apply($state);

    // Assert that the state was updated.
    expect($state->content)->toEqual('Default Content')
        ->and($state->is_locked)->toBeFalse()
        ->and($state->expires_at->diffInMinutes(Carbon::now()))->toBeLessThanOrEqual(1);
});
