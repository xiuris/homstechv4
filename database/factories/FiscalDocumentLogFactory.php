<?php

namespace Database\Factories;

use App\Models\FiscalDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

class FiscalDocumentLogFactory extends Factory
{
    public function definition(): array
    {
        return [
            'fiscal_document_id' => FiscalDocument::factory(),
            'status' => 'pending',
            'message' => 'Created for testing',
            'meta' => ['actor' => 'factory'],
        ];
    }
}
