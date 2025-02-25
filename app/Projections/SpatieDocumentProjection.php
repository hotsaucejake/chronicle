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
        // Ensure a valid UUID is set
        $attributes['uuid'] = $attributes['uuid'] ?? Uuid::uuid4()->toString();

        // Create a new instance
        $model = new static($attributes);

        // Convert it to a writeable instance before saving
        return $model->writeable();
    }
}
