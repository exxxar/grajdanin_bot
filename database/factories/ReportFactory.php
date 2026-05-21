<?php

namespace Database\Factories;

use App\Models\Municipality;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'type' => 0,
            'priority' => 0,
            'from_user_id' => User::factory(),
            'to_user_id' => User::factory(),
            'municipality_id' => Municipality::factory(),
            'received_at' => fake()->date(),
            'phone' => '+7' . fake()->numerify('##########'),
            'documents' => [],
        ];
    }
}
