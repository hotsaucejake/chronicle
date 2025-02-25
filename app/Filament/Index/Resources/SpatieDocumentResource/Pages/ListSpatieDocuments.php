<?php

namespace App\Filament\Index\Resources\SpatieDocumentResource\Pages;

use App\Filament\Index\Resources\SpatieDocumentResource;
use Filament\Resources\Pages\ListRecords;

class ListSpatieDocuments extends ListRecords
{
    protected static string $resource = SpatieDocumentResource::class;

    protected static ?string $navigationGroup = 'Spatie';
}
