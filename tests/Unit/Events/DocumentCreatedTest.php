<?php

use App\Events\Document\DocumentCreated;
use App\States\DocumentState;
use Carbon\Carbon;

beforeEach(function () {
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);
});

it('applies the DocumentCreated event to state', function () {
    // Create a fresh DocumentState.
    $state = new DocumentState;

    $event = new DocumentCreated;

    // Call apply() on the event.
    $event->apply($state);

    // Assert that the state was updated.
    expect($state->content)->toEqual('Default Content')
        ->and($state->is_locked)->toBeFalse()
        ->and($state->expires_at->diffInMinutes(Carbon::now()))->toBeLessThanOrEqual(1);
});
