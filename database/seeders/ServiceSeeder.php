<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        Service::insert([
            [
                'name' => 'Ganti Oli',
                'description' => 'Penggantian oli mesin kendaraan',
                'price' => 50000,
                'duration_minutes' => 30,
            ],
            [
                'name' => 'Servis Ringan',
                'description' => 'Pengecekan dan perawatan ringan kendaraan',
                'price' => 100000,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Servis Berat',
                'description' => 'Perbaikan menyeluruh pada kendaraan',
                'price' => 300000,
                'duration_minutes' => 120,
            ],
        ]);
    }
}