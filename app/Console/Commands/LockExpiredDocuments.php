<?php

namespace App\Console\Commands;

use App\Aggregates\SpatieDocumentAggregate;
use App\Contracts\Services\VerbsDocumentServiceInterface;
use App\Projections\SpatieDocumentProjection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;
use Thunk\Verbs\Facades\Verbs;

class LockExpiredDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chronicle:lock-expired-documents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lock expired documents and create a new document for revision';

    /**
     * Execute the console command.
     */
    public function handle(VerbsDocumentServiceInterface $documentService): void
    {
        $documentService->lockOpenExpiredVerbsDocuments();

        Verbs::commit();

        if ($documentService->livingVerbsDocumentsCount() === 0) {
            $this->info('No living documents found. Creating a new document for revision.');
            $documentService->createNewOpenVerbsDocument();
        }

        // First, "lock" any expired Spatie documents.
        // We assume that if a document hasn't been edited at all (no first or last editor and zero edits),
        // then we simply extend its expiration instead of locking it.
        SpatieDocumentProjection::query()
            ->where('expires_at', '<=', now())
            ->where('is_locked', false)
            ->get()
            ->each(function ($document) {
                if (
                    is_null($document->first_edit_user_id) &&
                    is_null($document->last_edit_user_id) &&
                    $document->edit_count === 0
                ) {
                    // Extend expiration if untouched.
                    $document->update([
                        'expires_at' => now()->addHours(Config::get('chronicle.document_expiration', 1)),
                    ]);
                } else {
                    if (empty($document->uuid)) {
                        $this->error("Document ID {$document->id} has an empty uuid");

                        return;
                    }

                    // Otherwise, lock the document via the aggregate.
                    SpatieDocumentAggregate::retrieve($document->uuid)
                        ->lockSpatieDocument($document->uuid)
                        ->persist();
                }
            });

        // Then, check if there are any living (i.e. unlocked) Spatie documents.
        $livingCount = SpatieDocumentProjection::query()
            ->where('is_locked', false)
            ->count();

        if ($livingCount === 0) {
            $this->info('No living Spatie documents found. Creating a new document for revision.');

            $uuid = Uuid::uuid4()->toString();

            // Prepare default attributes from your config.
            $attributes = [
                'uuid' => $uuid,
                'content' => Config::get('chronicle.initial_document_text', 'New Content'),
                'is_locked' => false,
                'expires_at' => now()->addHours(Config::get('chronicle.document_expiration', 1)),
                // You can set additional fields if needed.
            ];

            SpatieDocumentAggregate::retrieve($uuid)
                ->createSpatieDocument($attributes)
                ->persist();
        }
    }
}
