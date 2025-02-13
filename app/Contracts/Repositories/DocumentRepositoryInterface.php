<?php

namespace App\Contracts\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;

interface DocumentRepositoryInterface
{
    public function find(string $id): ?Document;

    public function create(array $data): Document;

    public function update(Document $document, array $data): Document;

    public function retrieveOpenExpiredDocuments(): Builder;

    public function livingDocumentsCount(): int;
}
