<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_revisions', function (Blueprint $table) {
            $table->snowflakeId();

            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->unsignedBigInteger('version')->default(1);

            $table->text('content');

            $table->foreignId('edited_by_user_id')->index()->constrained('users');

            $table->timestamp('edited_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_revisions');
    }
};
