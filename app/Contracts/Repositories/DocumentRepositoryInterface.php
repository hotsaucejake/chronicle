<?php

namespace App\Contracts\Repositories;

use App\Models\Document;
use Glhd\Bits\Snowflake;

interface DocumentRepositoryInterface
{
    public function find(Snowflake $id): Document;

    public function create(array $data): Document;

    public function update(Document $document, array $data): Document;
}
