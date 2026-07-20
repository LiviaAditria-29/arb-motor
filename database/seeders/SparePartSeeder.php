<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SparePart;

class SparePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SparePart::insert([
            [
                'name' => 'Oli Mesin',
                'price' => 60000,
                'stock' => 10,
                'unit' => 'liter',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kampas Rem',
                'price' => 80000,
                'stock' => 5,
                'unit' => 'pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Busi',
                'price' => 30000,
                'stock' => 20,
                'unit' => 'pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Filter Udara',
                'price' => 50000,
                'stock' => 8,
                'unit' => 'pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Aki',
                'price' => 250000,
                'stock' => 3,
                'unit' => 'pcs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}