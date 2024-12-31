<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 unique users using a factory
        User::factory(10)->create();

        // Create a unique test user only if it doesn't already exist
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'), // Ensure to set a password
        ]);

        // Call the seeders
        $this->call([
            // ProductSeeder::class,
            // ReviewSeeder::class,
        ]);
    }
}
