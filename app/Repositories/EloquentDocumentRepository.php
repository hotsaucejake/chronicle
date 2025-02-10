<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Models\Document;

class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function find(string $id): Document
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
