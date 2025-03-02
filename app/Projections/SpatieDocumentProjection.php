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
}
