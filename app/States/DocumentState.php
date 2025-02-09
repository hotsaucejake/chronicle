<?php

namespace App\States;

use Illuminate\Support\Carbon;
use Thunk\Verbs\State;

class DocumentState extends State
{
    // The current content of the document
    public string $content = '';

    // Indicates whether the document is locked for editing
    public bool $is_locked = false;

    // The timestamp when the document expires
    public ?Carbon $expires_at = null;

    public int $version = 1;
}
