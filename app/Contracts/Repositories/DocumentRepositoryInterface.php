<?php

namespace App\Contracts\Repositories;

use App\Models\Document;

interface DocumentRepositoryInterface
{
    public function find(int $id): Document;

    public function create(array $data): Document;

    public function update(Document $document, array $data): Document;
}
