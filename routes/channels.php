<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('spatie_document.{uuid}', function ($user, $uuid) {
    return true;
});

Broadcast::channel('verbs_document.{verbs_document_id}', function ($user, $verbs_document_id) {
    return true;
});
