<?php

namespace App\Projections;

use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Projections\Projection;

class SpatieDocumentProjection extends Projection
{
    protected $table = 'spatie_documents';

    protected $guarded = [];

    public static function uuid(string $uuid): self
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    public static function createWithAttributes(array $attributes): self
    {
        $attributes['uuid'] = Uuid::uuid4()->toString();

        return static::create($attributes);
    }
}
