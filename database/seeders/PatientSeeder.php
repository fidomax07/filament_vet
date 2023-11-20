<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Database\Seeder;


class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::factory()->count(30)->create();
    }
}
