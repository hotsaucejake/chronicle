<?php

namespace App\Contracts\Repositories;

use App\Models\VerbsDocument;
use Illuminate\Database\Eloquent\Builder;

interface DocumentRepositoryInterface
{
    public function find(string $id): ?VerbsDocument;

    public function create(array $data): VerbsDocument;

    public function update(VerbsDocument $document, array $data): VerbsDocument;

    public function retrieveOpenExpiredDocuments(): Builder;

    public function livingDocumentsCount(): int;
}
