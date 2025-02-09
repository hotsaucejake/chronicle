<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Model;

class DocumentRevision extends Model
{
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
}
