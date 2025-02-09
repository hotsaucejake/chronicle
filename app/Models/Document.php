<?php

namespace App\Models;

use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasSnowflakes;

    protected $fillable = [
        'content',
        'is_locked',
        'expires_at',
        'first_edit_user_id',
        'last_edit_user_id',
        'unique_editor_count',
        'edit_count',
        'last_edited_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => Snowflake::class,
            'is_locked' => 'boolean',
            'expires_at' => 'datetime',
            'first_edit_user_id' => Snowflake::class,
            'last_edit_user_id' => Snowflake::class,
            'unique_editor_count' => 'integer',
            'edit_count' => 'integer',
            'last_edited_at' => 'datetime',
        ];
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(DocumentRevision::class);
    }
}
