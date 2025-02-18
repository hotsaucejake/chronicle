<?php

namespace App\Console\Commands;

use App\Contracts\Services\DocumentRevisionServiceInterface;
use App\Contracts\Services\DocumentServiceInterface;
use App\Events\Document\Verbs\DocumentEdited;
use App\Models\Document;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Thunk\Verbs\Facades\Verbs;

class WorkflowTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chronicle:workflow-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A manual workflow test to see output in the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        config()->set('chronicle.initial_document_text', 'Default Content');
        config()->set('chronicle.document_expiration', 1);

        $documentService = app(DocumentServiceInterface::class);
        $documentRevisionService = app(DocumentRevisionServiceInterface::class);

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Auth::login($user);

        Artisan::call('chronicle:lock-expired-documents');
        Verbs::commit();

        $document = Document::where('is_locked', false)
            ->latest()
            ->first();

        // 2. Edit the document.
        DocumentEdited::fire(
            document_id: $document->id,
            new_content: 'First edit content',
            previous_version: $documentRevisionService->getMaxVersionDocumentRevisionByDocumentId($document->id) + 1
        );
        Verbs::commit();

        $document = Document::find($document->id);

        DocumentEdited::fire(
            document_id: $document->id,
            new_content: 'Second edit content',
            previous_version: $documentRevisionService->getMaxVersionDocumentRevisionByDocumentId($document->id) + 1
        );
        Verbs::commit();

        Auth::logout();
        Auth::login($user2);

        $document = Document::find($document->id);

        DocumentEdited::fire(
            document_id: $document->id,
            new_content: 'Third edit content',
            previous_version: $documentRevisionService->getMaxVersionDocumentRevisionByDocumentId($document->id) + 1
        );
        Verbs::commit();

        $document = Document::find($document->id);

        // 3. Simulate expiration by updating expires_at to the past.
        $document->update(['expires_at' => now()->subMinute()]);

        // 4. Run the scheduled command.
        Artisan::call('chronicle:lock-expired-documents');
        Verbs::commit();
    }
}
