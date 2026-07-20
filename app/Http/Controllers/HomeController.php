<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\SparePart;
use App\Models\Booking;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $services     = Service::take(3)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->take(6)->get();
        $stats = [
            'total_services'    => Service::count(),
            'total_spare_parts' => SparePart::count(),
            'total_bookings'    => Booking::count(),
            'total_customers'   => 0,
        ];

        return view('home', compact('services', 'testimonials', 'stats'));
    }
}