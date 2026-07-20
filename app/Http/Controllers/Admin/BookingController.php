<?php
// app/Http/Controllers/Admin/BookingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Service, Vehicle};
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer','service'])->latest('booking_date');

        if ($request->filled('status'))
            $query->where('status', $request->status);

        if ($request->filled('search'))
            $query->where(function($q) use ($request) {
                $q->where('customer_name','like','%'.$request->search.'%')
                  ->orWhere('vehicle_name','like','%'.$request->search.'%')
                  ->orWhereHas('service', fn($sq) => $sq->where('name','like','%'.$request->search.'%'));
            });

        if ($request->filled('date'))
            $query->whereDate('booking_date', $request->date);

        if ($request->filled('month') && $request->filled('year'))
            $query->whereMonth('booking_date', $request->month)
                  ->whereYear('booking_date', $request->year);

        $bookings = $query->paginate(15)->withQueryString();
        $services = Service::orderBy('name')->get();
        $statuses = ['pending','confirmed','in_progress','completed','taken','cancelled'];

        return view('admin.bookings.index', compact('bookings','services','statuses'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services  = Service::orderBy('name')->get();
        $vehicles  = Vehicle::with('customer')->get();
        return view('admin.bookings.create', compact('customers','services','vehicles'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:20',
            'vehicle_name'    => 'required|string|max:255',
            'service_id'      => 'required|exists:services,id',
            'booking_date'    => 'required|date',
            'time_slot'       => 'required',
            'estimated_cost'  => 'nullable|integer|min:0',
            'technician_name' => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        // Auto-fill estimated_cost dari harga service jika tidak diisi
        if (empty($v['estimated_cost'])) {
            $service = Service::find($v['service_id']);
            if ($service) {
                $v['estimated_cost'] = $service->price;
            }
        }

        Booking::create($v);
        return redirect()->route('admin.bookings.index')
                         ->with('success', 'Booking berhasil ditambahkan!');
    }

    public function show($id)
    {
        $booking = Booking::with(['customer','service','vehicle'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking   = Booking::findOrFail($id);
        $services  = Service::orderBy('name')->get();
        $customers = Customer::orderBy('name')->get();
        $statuses  = ['pending','confirmed','in_progress','completed','taken','cancelled'];
        return view('admin.bookings.edit', compact('booking','services','customers','statuses'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $v = $request->validate([
            'customer_name'   => 'required|string|max:255',
            'customer_phone'  => 'required|string|max:20',
            'vehicle_name'    => 'required|string|max:255',
            'service_id'      => 'required|exists:services,id',
            'booking_date'    => 'required|date',
            'time_slot'       => 'required',
            'status'          => 'required|in:pending,confirmed,in_progress,completed,taken,cancelled',
            'estimated_cost'  => 'nullable|integer|min:0',
            'actual_cost'     => 'nullable|integer|min:0',
            'technician_name' => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
        ]);

        if ($v['status'] === 'completed' && !$booking->completed_at) {
            $v['completed_at'] = now();
        }

        $booking->update($v);
        return redirect()->route('admin.bookings.index')
                         ->with('success', 'Booking berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return redirect()->route('admin.bookings.index')
                         ->with('success', 'Booking berhasil dihapus!');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,confirmed,in_progress,completed,taken,cancelled']);

        $data = ['status' => $request->status];
        if ($request->status === 'completed' && !$booking->completed_at) {
            $data['completed_at'] = now();
        }

        $booking->update($data);
        return redirect()->back()->with('success', 'Status booking diperbarui!');
    }
}