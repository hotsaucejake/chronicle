<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DocumentEditData extends Data
{
    public function __construct(
        #[Required]
        public int $document_id,

        #[Required]
        public string $new_content,

        #[Required]
        public int $previous_version,

        #[FromAuthenticatedUserProperty('id')]
        public int $editor_id
    ) {}
}
