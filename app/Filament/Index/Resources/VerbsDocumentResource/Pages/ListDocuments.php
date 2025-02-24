<?php

namespace App\Filament\Index\Resources\DocumentResource\Pages;

use App\Filament\Index\Resources\VerbsDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = VerbsDocumentResource::class;

    protected static ?string $navigationGroup = 'Verbs';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
