<?php

namespace App\Models;

use Database\Factories\DocumentRevisionFactory;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseFactory(DocumentRevisionFactory::class)]
class DocumentRevision extends Model
{
    use HasFactory;
    use HasSnowflakes;

    protected $fillable = [
        'document_id',
        'version',
        'content',
        'edited_by_user_id',
        'edited_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => Snowflake::class,
            'document_id' => Snowflake::class,
            'version' => 'integer',
            'edited_by_user_id' => Snowflake::class,
            'edited_at' => 'datetime',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }
}
