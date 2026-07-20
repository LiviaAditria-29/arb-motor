<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['customer_name' => 'Budi Santoso',   'vehicle' => 'Toyota Avanza 2019',  'rating' => 5, 'comment' => 'Servis sangat cepat dan mekanik sangat profesional. Mobil saya langsung terasa lebih responsif setelah tune up. Sangat puas!'],
            ['customer_name' => 'Sari Rahayu',    'vehicle' => 'Honda Jazz 2020',     'rating' => 5, 'comment' => 'Ganti oli di ARB Motor sangat mudah, tinggal booking online dan langsung dilayani. Harganya juga sangat terjangkau dan transparan.'],
            ['customer_name' => 'Dendi Pratama',  'vehicle' => 'Suzuki Ertiga 2018',  'rating' => 4, 'comment' => 'Pelayanan ramah dan teknisinya berpengalaman. Masalah AC saya yang sudah lama tidak dingin akhirnya bisa diselesaikan dalam sehari.'],
            ['customer_name' => 'Rina Wulandari', 'vehicle' => 'Mitsubishi Xpander',  'rating' => 5, 'comment' => 'ARB Motor adalah bengkel terpercaya yang pernah saya kunjungi. Harga fair, pengerjaan rapi, dan hasilnya memuaskan!'],
            ['customer_name' => 'Hendra Kusuma',  'vehicle' => 'Toyota Rush 2021',    'rating' => 5, 'comment' => 'Chatbot-nya sangat membantu untuk konsultasi sebelum datang. Estimasi biayanya akurat dan tidak ada biaya tersembunyi. Top!'],
            ['customer_name' => 'Dewi Anggraini', 'vehicle' => 'Honda CRV 2017',      'rating' => 4, 'comment' => 'Spare part yang digunakan original dan bergaransi. Mekaniknya juga sabar menjelaskan kondisi kendaraan saya. Pasti balik lagi!'],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create(array_merge($t, ['is_active' => true]));
        }
    }
}