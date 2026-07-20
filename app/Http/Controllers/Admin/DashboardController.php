<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Service, SparePart};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $now = now();

    $stats = [
        'total_bookings'     => \Illuminate\Support\Facades\Schema::hasTable('bookings')  ? \App\Models\Booking::count()    : 0,
        'booking_this_month' => \Illuminate\Support\Facades\Schema::hasTable('bookings')  ? \App\Models\Booking::whereMonth('booking_date', $now->month)->whereYear('booking_date', $now->year)->count() : 0,
        'pending'            => \Illuminate\Support\Facades\Schema::hasTable('bookings')  ? \App\Models\Booking::where('status','pending')->count()   : 0,
        'completed'          => \Illuminate\Support\Facades\Schema::hasTable('bookings')  ? \App\Models\Booking::where('status','completed')->count() : 0,
        'total_customers'    => \Illuminate\Support\Facades\Schema::hasTable('customers') ? \App\Models\Customer::count()   : 0,
        'total_services'     => \Illuminate\Support\Facades\Schema::hasTable('services')  ? \App\Models\Service::count()    : 0,
        'total_spare_parts'  => \Illuminate\Support\Facades\Schema::hasTable('spare_parts') ? \App\Models\SparePart::count() : 0,
        'low_stock'          => \Illuminate\Support\Facades\Schema::hasTable('spare_parts') ? \App\Models\SparePart::where('stock','<=',3)->where('stock','>',0)->count() : 0,
        'out_of_stock'       => \Illuminate\Support\Facades\Schema::hasTable('spare_parts') ? \App\Models\SparePart::where('stock',0)->count() : 0,
        'revenue_this_month' => 0,
    ];

    $chartLabels  = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $chartBooking = array_fill(0, 12, 0);
    $chartRevenue = array_fill(0, 12, 0);

    if (\Illuminate\Support\Facades\Schema::hasTable('bookings')) {
        $monthlyData = \App\Models\Booking::select(
                \Illuminate\Support\Facades\DB::raw('MONTH(booking_date) as month'),
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'),
                \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN status="completed" THEN actual_cost ELSE 0 END) as revenue')
            )
            ->whereYear('booking_date', $now->year)
            ->groupBy('month')
            ->get()->keyBy('month');

        for ($m = 1; $m <= 12; $m++) {
            $chartBooking[$m-1] = $monthlyData[$m]->total   ?? 0;
            $chartRevenue[$m-1] = $monthlyData[$m]->revenue ?? 0;
        }

        $stats['revenue_this_month'] = \App\Models\Booking::where('status','completed')
            ->whereMonth('booking_date', $now->month)
            ->whereYear('booking_date', $now->year)
            ->sum('actual_cost');
    }

    $recentBookings  = \Illuminate\Support\Facades\Schema::hasTable('bookings')
        ? \App\Models\Booking::latest()->take(8)->get()
        : collect();

    $statusBreakdown = \Illuminate\Support\Facades\Schema::hasTable('bookings')
        ? \App\Models\Booking::select('status', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('status')->pluck('total','status')
        : collect();

        $topServices = \Illuminate\Support\Facades\Schema::hasTable('bookings')
        ? \App\Models\Booking::select(
                'services.name as service_name',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total')
            )
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->whereNotNull('bookings.service_id')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('total')
            ->take(5)
            ->get()
        : collect();

    return view('admin.dashboard', compact(
        'stats','chartLabels','chartBooking','chartRevenue',
        'recentBookings','statusBreakdown','topServices'
    ));
}
}
