<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Chiama il seeder dell'hotel
        $this->call([
            HotelSeeder::class,
        ]);
    }
}
