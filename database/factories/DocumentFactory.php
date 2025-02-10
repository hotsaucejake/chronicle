<?php

namespace Database\Factories;

use App\Models\Document;
use Glhd\Bits\Factories\SnowflakeFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    protected $model = Document::class;

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
