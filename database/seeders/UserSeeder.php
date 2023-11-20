<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Fidan Ademi',
            'email' => 'fidomax07@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        User::factory()->count(9)->create();
    }
}
