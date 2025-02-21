<?php

use App\Events\Document\VerbsDocumentEditingBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

test('broadcast channel', function () {
    $event = new VerbsDocumentEditingBroadcast(verbs_document_id: 123, username: 'johndoe');
    $channels = $event->broadcastOn();

    $this->assertIsArray($channels);
    $this->assertInstanceOf(PrivateChannel::class, $channels[0]);
    $this->assertEquals('private-verbs_document.123', $channels[0]->name);
});
