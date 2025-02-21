<?php

namespace Database\Factories;

use App\Models\VerbsDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VerbsDocument>
 */
class VerbsDocumentFactory extends Factory
{
    protected $model = VerbsDocument::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->paragraph,
            'is_locked' => false,
            'expires_at' => now()->addHours(config('chronicle.document_expiration')),
        ];
    }
}
