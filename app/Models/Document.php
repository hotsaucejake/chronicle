<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Glhd\Bits\Database\HasSnowflakes;
use Glhd\Bits\Snowflake;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseFactory(DocumentFactory::class)]
class Document extends Model
{
    use HasFactory;
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
            'is_locked' => 'boolean',
            'expires_at' => 'datetime',
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
