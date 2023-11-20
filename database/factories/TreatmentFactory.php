<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TreatmentFactory extends Factory
{
    protected $model = Treatment::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'notes' => $this->faker->word(),
            'patient_id' => Patient::inRandomOrder()->first() ?? Patient::factory(),
            'price' => $this->faker->randomNumber(5),
            'created_at' => Carbon::now()
                ->subMonths(mt_rand(0, 12))
                ->subDays(mt_rand(0, 28)),
            'updated_at' => Carbon::now(),
        ];
    }
}
