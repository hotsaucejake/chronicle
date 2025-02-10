<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Models\Document;
use Glhd\Bits\Snowflake;

class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function find(Snowflake $id): Document
    {
        return Document::findOrFail($id);
    }

    public function create(array $data): Document
    {
        return Document::create($data);
    }

    public function update(Document $document, array $data): Document
    {
        $document->update($data);

        return $document->fresh();
    }
}
