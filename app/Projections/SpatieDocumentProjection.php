<?php

namespace App\Projections;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EventSourcing\Projections\Projection;
use Spatie\EventSourcing\Snapshots\EloquentSnapshot;

class SpatieDocumentProjection extends Projection
{
    protected $table = 'spatie_documents';

    protected $guarded = [];

    public static function uuid(string $uuid): self
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(EloquentSnapshot::class, 'aggregate_uuid', 'uuid');
    }
}
