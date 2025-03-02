<?php

namespace App\Filament\Index\Resources\SpatieDocumentResource\Pages;

use App\Aggregates\SpatieDocumentAggregate;
use App\Events\Document\SpatieDocumentEditedBroadcast;
use App\Events\Document\SpatieDocumentEditingBroadcast;
use App\Filament\Index\Resources\SpatieDocumentResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditSpatieDocument extends EditRecord
{
    protected static string $resource = SpatieDocumentResource::class;

    protected static string $view = 'filament.index.resources.spatie-document-resource.pages.spatie-edit-document';

    public string $content;

    public function pingEditing(): void
    {
        SpatieDocumentEditingBroadcast::dispatch($this->getRecord()->uuid, auth()->user()->username);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $newContent = $data['content'] ?? $record->content;

        // Retrieve and update the aggregate for this document
        SpatieDocumentAggregate::retrieve($record->uuid)
            ->editSpatieDocument($newContent, $record->version, Auth::user()->id)
            ->persist();

        event(new SpatieDocumentEditedBroadcast(
            uuid: $record->uuid,
            new_content: $newContent,
        ));

        return $record->fresh();
    }
}
