<?php

namespace App\Repositories;

use App\Contracts\Repositories\DocumentRepositoryInterface;
use App\Models\VerbsDocument;
use Illuminate\Database\Eloquent\Builder;

class EloquentDocumentRepository implements DocumentRepositoryInterface
{
    public function find(string $id): ?VerbsDocument
    {
        return VerbsDocument::find($id);
    }

    public function create(array $data): VerbsDocument
    {
        return VerbsDocument::create($data);
    }

    public function update(VerbsDocument $document, array $data): VerbsDocument
    {
        $document->update($data);

        return $document->fresh();
    }

    public function retrieveOpenExpiredDocuments(): Builder
    {
        return VerbsDocument::whereDate('expires_at', '<=', now())
            ->where('is_locked', false);
    }

    public function livingDocumentsCount(): int
    {
        return VerbsDocument::where('is_locked', false)
            ->count();
    }
}
