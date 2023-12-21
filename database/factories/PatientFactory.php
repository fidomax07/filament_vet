<?php

namespace Database\Factories;

use App\Models\Owner;
use App\Models\Patient;
use App\Enums\PatientType;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'date_of_birth' => Carbon::now()
                ->subYears(mt_rand(0, 10))
                ->subMonths(mt_rand(0, 12))
                ->subDays(mt_rand(0, 28)),
            'name' => $this->faker->firstName,
            'owner_id' => Owner::inRandomOrder()->first() ?? Owner::factory(),
            'type' => $this->faker->randomElement(PatientType::values()),
            'is_approved' => $approved = $this->faker->randomElement([false, true]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
