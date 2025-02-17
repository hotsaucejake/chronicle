<?php

namespace App\Filament\Index\Resources\DocumentResource\Pages;

use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Events\Document\DocumentEdited;
use App\Filament\Index\Resources\DocumentResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\Facades\Verbs;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected static string $view = 'filament.index.resources.document-resource.pages.edit-document';

    public string $content;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $documentRevisionService = app(DocumentRevisionServiceInterface::class);
        $document = $this->record;

        $newContent = $data['content'] ?? $document->content;

        $previousVersion = $documentRevisionService->getMaxVersionDocumentRevisionByDocumentId($document->id) + 1;

        DocumentEdited::fire(
            document_id: $document->id,
            new_content: $newContent,
            previous_version: $previousVersion
        );

        Verbs::commit();

        return $record->fresh();
    }
}
