<?php

namespace App\Filament\Index\Resources\VerbsDocumentResource\Pages;

use App\Filament\Index\Resources\VerbsDocumentResource;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = VerbsDocumentResource::class;

    protected static ?string $navigationGroup = 'Verbs';
}
