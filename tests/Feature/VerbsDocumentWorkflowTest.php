<?php

use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Events\Document\Verbs\VerbsDocumentEdited;
use App\Models\User;
use App\Models\VerbsDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Thunk\Verbs\Facades\Verbs;

it('simulates a full document workflow', function () {
    // Set config values for testing.
    config()->set('chronicle.initial_document_text', 'Default Content');
    config()->set('chronicle.document_expiration', 1);
    $documentService = app(VerbsDocumentServiceInterface::class);
    $documentRevisionService = app(VerbsDocumentRevisionServiceInterface::class);

    // Create a user and act as that user.
    $user = User::factory()->create();
    $user2 = User::factory()->create();
    $this->actingAs($user);

    // 1. Create a new document by firing VerbsDocumentCreated.
    Artisan::call('chronicle:lock-expired-documents');
    Verbs::commit();

    $document = VerbsDocument::where('is_locked', false)
        ->latest()
        ->first();

    expect($document->content)->toEqual('Default Content');

    // 2. Edit the document.
    VerbsDocumentEdited::fire(
        verbs_document_id: $document->id,
        new_content: 'First edit content',
        previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
        editor_id: $user->id
    );
    Verbs::commit();

    $document = VerbsDocument::find($document->id);
    expect($document->content)->toEqual('First edit content')
        ->and($document->edit_count)->toEqual(1)
        ->and($document->first_edit_user_id)->toEqual($user->id)
        ->and($document->last_edit_user_id)->toEqual($user->id)
        ->and($document->unique_editor_count)->toEqual(1);

    VerbsDocumentEdited::fire(
        verbs_document_id: $document->id,
        new_content: 'Second edit content',
        previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
        editor_id: $user->id
    );
    Verbs::commit();

    $document = VerbsDocument::find($document->id);
    expect($document->content)->toEqual('Second edit content')
        ->and($document->edit_count)->toEqual(2)
        ->and($document->first_edit_user_id)->toEqual($user->id)
        ->and($document->last_edit_user_id)->toEqual($user->id)
        ->and($document->unique_editor_count)->toEqual(1);

    $this->actingAs($user2);

    VerbsDocumentEdited::fire(
        verbs_document_id: $document->id,
        new_content: 'Third edit content',
        previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
        editor_id: $user2->id
    );
    Verbs::commit();

    $document = VerbsDocument::find($document->id);
    expect($document->content)->toEqual('Third edit content')
        ->and($document->edit_count)->toEqual(3)
        ->and($document->first_edit_user_id)->toEqual($user->id)
        ->and($document->last_edit_user_id)->toEqual($user2->id)
        ->and($document->unique_editor_count)->toEqual(2);

    // 3. Simulate expiration by updating expires_at to the past.
    $document->update(['expires_at' => Carbon::now()->subMinute()]);

    // 4. Run the scheduled command.
    Artisan::call('chronicle:lock-expired-documents');
    Verbs::commit();

    $document = VerbsDocument::find($document->id);
    expect($document->is_locked)->toBeTrue();
});
