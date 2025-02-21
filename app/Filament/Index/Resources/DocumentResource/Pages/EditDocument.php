<?php

namespace App\Filament\Index\Resources\DocumentResource\Pages;

use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Events\Document\DocumentEditingBroadcast;
use App\Events\Document\Verbs\VerbsDocumentEdited;
use App\Filament\Index\Resources\DocumentResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Thunk\Verbs\Facades\Verbs;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    protected static string $view = 'filament.index.resources.document-resource.pages.edit-document';

    public string $content;

    public function pingEditing(): void
    {
        DocumentEditingBroadcast::dispatch($this->getRecord()->id, auth()->user()->username);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $documentRevisionService = app(VerbsDocumentRevisionServiceInterface::class);
        $document = $this->record;

        $newContent = $data['content'] ?? $document->content;

        $previousVersion = $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1;

        VerbsDocumentEdited::fire(
            verbs_document_id: $document->id,
            new_content: $newContent,
            previous_version: $previousVersion
        );

        Verbs::commit();

        return $record->fresh();
    }
}
