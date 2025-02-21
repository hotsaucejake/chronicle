<?php

namespace App\Models;

use Database\Factories\VerbsDocumentRevisionFactory;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(VerbsDocumentRevisionFactory::class)]
class VerbsDocumentRevision extends Model
{
    use HasFactory;
    use HasSnowflakes;

    protected $fillable = [
        'verbs_document_id',
        'version',
        'content',
        'edited_by_user_id',
        'edited_at',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'edited_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(VerbsDocument::class);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }
}
