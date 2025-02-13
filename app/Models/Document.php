<?php

namespace App\Models;

use Database\Factories\DocumentFactory;
use Glhd\Bits\Database\HasSnowflakes;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseFactory(DocumentFactory::class)]
class Document extends Model
{
    use HasFactory;
    use HasSnowflakes;
    use SoftDeletes;

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

    public function firstEditUser(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'first_edit_user_id');
    }

    public function lastEditUser(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'last_edit_user_id');
    }
}
