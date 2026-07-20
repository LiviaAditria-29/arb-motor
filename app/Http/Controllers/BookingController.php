<?php
// ============================================================
// FILE: app/Http/Controllers/BookingController.php
// ============================================================

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('booking.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'vehicle_name'   => 'required|string|max:255',
            'booking_date'   => 'required|date|after:today',
            'time_slot'      => 'required|string',
            'notes'          => 'nullable|string',
        ]);

        // Ambil nama layanan
        $serviceName = $request->input('service_name');
        if ($request->filled('service_id')) {
            $service     = Service::find($request->service_id);
            $serviceName = $service?->name ?? 'Layanan Umum';
            $validated['estimated_cost'] = $service?->price ?? 0;
        }

        $validated['vehicle_name'] = $validated['vehicle_name'];
        $validated['status']       = 'pending';

        Booking::create(array_merge($validated, ['vehicle_name' => $validated['vehicle_name']]));

        return redirect()->route('home')
                         ->with('success', 'Booking berhasil! Kami akan menghubungi Anda segera.');
    }
}
