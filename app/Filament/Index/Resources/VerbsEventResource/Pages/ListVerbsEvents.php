<?php

namespace App\Filament\Index\Resources\VerbsEventResource\Pages;

use App\Filament\Index\Resources\VerbsEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerbsEvents extends ListRecords
{
    protected static string $resource = VerbsEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
