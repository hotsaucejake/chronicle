<?php

namespace App\Data;

use Spatie\LaravelData\Attributes\FromAuthenticatedUserProperty;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class VerbsDocumentEditData extends Data
{
    public function __construct(
        #[Required]
        public string $verbs_document_id,

        #[Required]
        public string $new_content,

        #[Required]
        public int    $previous_version,

        #[FromAuthenticatedUserProperty(property: 'id')]
        public string $editor_id
    ) {}
}
