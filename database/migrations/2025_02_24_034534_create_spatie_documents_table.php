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
        Schema::create('spatie_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique()->index();
            $table->text('content')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->foreignId('first_edit_user_id')->nullable()->constrained('users');
            $table->foreignId('last_edit_user_id')->nullable()->constrained('users');
            $table->unsignedBigInteger('unique_editor_count')->default(0);
            $table->unsignedBigInteger('edit_count')->default(0);
            $table->unsignedBigInteger('version')->default(1);
            $table->timestamp('last_edited_at')->nullable();

            // column to hold an array of unique editor IDs
            $table->json('editor_ids')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spatie_documents');
    }
};
