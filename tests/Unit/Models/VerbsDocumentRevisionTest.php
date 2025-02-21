<?php

use App\Models\User;
use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;

test('revision belongs to document and editor', function () {
    $document = VerbsDocument::factory()->create();
    $user = User::factory()->create();

    $revision = VerbsDocumentRevision::factory()->create([
        'verbs_document_id' => $document->id,
        'version' => 1,
        'content' => 'Edit content',
        'edited_by_user_id' => $user->id,
    ]);

    $this->assertEquals($document->id, $revision->verbsDocument->id);
    $this->assertEquals($user->id, $revision->editor->id);
});
