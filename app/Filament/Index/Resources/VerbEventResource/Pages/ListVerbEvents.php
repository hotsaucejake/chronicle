<?php

namespace App\Filament\Index\Resources\VerbEventResource\Pages;

use App\Filament\Index\Resources\VerbEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVerbEvents extends ListRecords
{
    protected static string $resource = VerbEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
