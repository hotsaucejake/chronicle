<?php

namespace App\Console\Commands;

use App\Contracts\Services\DocumentServiceInterface;
use Illuminate\Console\Command;

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
    public function handle(DocumentServiceInterface $documentService): void
    {
        $documentService->lockOpenExpiredDocuments();

        if ($documentService->livingDocumentsCount() === 0) {
            $this->info('No living documents found. Creating a new document for revision.');
            $documentService->createNewOpenDocument();
        }
    }
}
