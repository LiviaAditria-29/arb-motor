<?php
// ============================================================
// FILE: database/seeders/DatabaseSeeder.php
// Tambahkan TestimonialSeeder ke dalam run()
// ============================================================

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TestimonialSeeder::class,
        ]);
    }
}
