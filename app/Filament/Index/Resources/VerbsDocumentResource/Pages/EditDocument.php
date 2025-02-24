<?php

namespace App\Filament\Index\Resources\VerbsDocumentResource\Pages;

use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Events\Document\Verbs\VerbsDocumentEdited;
use App\Events\Document\VerbsDocumentEditingBroadcast;
use App\Filament\Index\Resources\VerbsDocumentResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Thunk\Verbs\Facades\Verbs;

class EditDocument extends EditRecord
{
    protected static string $resource = VerbsDocumentResource::class;

    protected static string $view = 'filament.index.resources.document-resource.pages.edit-document';

    public string $content;

    public function pingEditing(): void
    {
        VerbsDocumentEditingBroadcast::dispatch($this->getRecord()->id, auth()->user()->username);
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
            previous_version: $previousVersion,
            editor_id: Auth::user()->id
        );

        Verbs::commit();

        return $record->fresh();
    }
}
