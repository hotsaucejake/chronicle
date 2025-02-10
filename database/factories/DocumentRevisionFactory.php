<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentRevision>
 */
class DocumentRevisionFactory extends Factory
{
    protected $model = DocumentRevision::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_id' => Document::factory(),
            'version' => 1,
            'content' => fake()->paragraph,
            'edited_by_user_id' => User::factory(),
            'edited_at' => now(),
        ];
    }
}
