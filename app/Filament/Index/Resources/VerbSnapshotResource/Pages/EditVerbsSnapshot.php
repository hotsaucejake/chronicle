<?php

namespace App\Filament\Index\Resources\VerbSnapshotResource\Pages;

use App\Filament\Index\Resources\VerbSnapshotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerbsSnapshot extends EditRecord
{
    protected static string $resource = VerbSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
