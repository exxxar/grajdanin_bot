<?php

namespace Database\Factories;

use App\Models\IncomingReport;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomingReportFactory extends Factory
{
    protected $model = IncomingReport::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'received_from' => fake()->name(),
            'problem_description' => fake()->paragraph(),
            'help_formats' => [],
            'comment' => fake()->optional()->sentence(),
            'problems' => [],
            'solutions' => [],
            'difficulties' => [],
            'audio_files' => [],
        ];
    }
}
