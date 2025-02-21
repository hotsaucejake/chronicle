<?php

namespace Database\Factories;

use App\Models\VerbsDocument;
use App\Models\VerbsDocumentRevision;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VerbsDocumentRevision>
 */
class VerbsDocumentRevisionFactory extends Factory
{
    protected $model = VerbsDocumentRevision::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'verbs_document_id' => VerbsDocument::factory(),
            'version' => 1,
            'content' => fake()->paragraph,
            'edited_by_user_id' => User::factory(),
            'edited_at' => now(),
        ];
    }
}
