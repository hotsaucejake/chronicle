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
        Schema::create('verbs_documents', function (Blueprint $table) {
            $table->snowflakeId();
            $table->text('content')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->timestamp('expires_at')->nullable();

            $table->foreignId('first_edit_user_id')->nullable()->constrained('users');
            $table->foreignId('last_edit_user_id')->nullable()->constrained('users');
            $table->unsignedBigInteger('unique_editor_count')->default(0);
            $table->unsignedBigInteger('edit_count')->default(0);
            $table->timestamp('last_edited_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verbs_documents');
    }
};
