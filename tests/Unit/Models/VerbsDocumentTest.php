<?php

use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;

test('document creation and revisions relationship', function () {
    $document = VerbsDocument::factory()->create(['content' => 'Initial content']);

    // Assuming your factory for revisions exists.
    $revision = VerbsDocumentRevision::factory()->create([
        'verbs_document_id' => $document->id,
        'version' => 1,
        'content' => 'Initial content',
    ]);

    $this->assertEquals('Initial content', $document->content);
    $this->assertCount(1, $document->revisions);
});
