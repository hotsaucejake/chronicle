<?php

namespace App\Console\Commands;

use App\Contracts\Services\VerbsDocumentRevisionServiceInterface;
use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Events\Document\Verbs\VerbsDocumentEdited;
use App\Models\User;
use App\Models\VerbsDocument;
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

        $documentService = app(VerbsDocumentServiceInterface::class);
        $documentRevisionService = app(VerbsDocumentRevisionServiceInterface::class);

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Auth::login($user);

        Artisan::call('chronicle:lock-expired-documents');
        Verbs::commit();

        $document = VerbsDocument::where('is_locked', false)
            ->latest()
            ->first();

        // 2. Edit the document.
        VerbsDocumentEdited::fire(
            verbs_document_id: $document->id,
            new_content: 'First edit content',
            previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
            editor_id: $user->id
        );
        Verbs::commit();

        $document = VerbsDocument::find($document->id);

        VerbsDocumentEdited::fire(
            verbs_document_id: $document->id,
            new_content: 'Second edit content',
            previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
            editor_id: $user->id
        );
        Verbs::commit();

        Auth::logout();
        Auth::login($user2);

        $document = VerbsDocument::find($document->id);

        VerbsDocumentEdited::fire(
            verbs_document_id: $document->id,
            new_content: 'Third edit content',
            previous_version: $documentRevisionService->getMaxVersionVerbsDocumentRevisionByVerbsDocumentId($document->id) + 1,
            editor_id: $user2->id
        );
        Verbs::commit();

        $document = VerbsDocument::find($document->id);

        // 3. Simulate expiration by updating expires_at to the past.
        $document->update(['expires_at' => now()->subMinute()]);

        // 4. Run the scheduled command.
        Artisan::call('chronicle:lock-expired-documents');
        Verbs::commit();
    }
}
