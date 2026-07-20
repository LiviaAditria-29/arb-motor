<?php
// app/Http/Controllers/Admin/ReportController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Customer, Service, SparePart};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Halaman pilih bulan/tahun untuk rekap
     */
    public function index(Request $request)
    {
        $month = (int)($request->month ?? now()->month);
        $year  = (int)($request->year  ?? now()->year);

        $data = $this->buildReportData($month, $year);

        // Daftar tahun yang ada data booking
        $availableYears = Booking::selectRaw('YEAR(booking_date) as year')
                                 ->distinct()->orderByDesc('year')
                                 ->pluck('year');

        return view('admin.reports.index', compact('data','month','year','availableYears'));
    }

    /**
     * Export rekap sebagai PDF (download / print)
     */
    public function exportPdf(Request $request)
    {
        $month = (int)($request->month ?? now()->month);
        $year  = (int)($request->year  ?? now()->year);

        $data = $this->buildReportData($month, $year);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('data','month','year'))
                  ->setPaper('a4','portrait')
                  ->setOptions([
                      'defaultFont'  => 'sans-serif',
                      'isRemoteEnabled' => false,
                      'dpi' => 120,
                  ]);

        $filename = 'Rekap_ARBMotor_'.date('F_Y', mktime(0,0,0,$month,1,$year)).'.pdf';
        return $pdf->download($filename);
    }

    /**
     * Preview rekap di browser (untuk print manual)
     */
    public function previewPdf(Request $request)
    {
        $month = (int)($request->month ?? now()->month);
        $year  = (int)($request->year  ?? now()->year);

        $data = $this->buildReportData($month, $year);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('data','month','year'))
                  ->setPaper('a4','portrait');

        return $pdf->stream('Rekap_ARBMotor.pdf');
    }

    /**
     * Bangun semua data rekap untuk bulan & tahun tertentu
     */
    private function buildReportData(int $month, int $year): array
{
    $bookings = Booking::with(['service'])
                    ->whereMonth('booking_date', $month)
                    ->whereYear('booking_date', $year)
                    ->orderBy('booking_date')
                    ->orderBy('time_slot')
                    ->get();

        $completed = $bookings->where('status', 'completed');
        $cancelled = $bookings->where('status', 'cancelled');

        $byService = $bookings->groupBy('display_service')
                            ->map(fn($g) => [
                                'service' => $g->first()->display_service,
                                'total'   => $g->count(),
                                'selesai' => $g->where('status', 'completed')->count(),
                                'batal'   => $g->where('status', 'cancelled')->count(),
                                'revenue' => $g->where('status', 'completed')->sum('actual_cost'),
                            ])->values();

        $byStatus = $bookings->groupBy('status')->map(fn($g) => $g->count());

        $byDay = $bookings->groupBy(fn($b) => $b->booking_date->format('Y-m-d'))
                        ->map(fn($g) => [
                            'date'    => $g->first()->booking_date->format('d M Y'),
                            'total'   => $g->count(),
                            'selesai' => $g->where('status', 'completed')->count(),
                            'revenue' => $g->where('status', 'completed')->sum('actual_cost'),
                        ])->values();

        return [
            'bookings'        => $bookings,
            'total_bookings'  => $bookings->count(),
            'total_completed' => $completed->count(),
            'total_cancelled' => $cancelled->count(),
            'total_pending'   => $bookings->whereIn('status', ['pending','confirmed','in_progress'])->count(),
            'total_revenue'   => $completed->sum('actual_cost'),
            'avg_revenue'     => $completed->count() > 0
                                ? $completed->sum('actual_cost') / $completed->count() : 0,
            'by_service'      => $byService,
            'by_status'       => $byStatus,
            'by_day'          => $byDay,
            'month_name'      => \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y'),
            'generated_at'    => now()->format('d/m/Y H:i'),
            'generated_by'    => auth()->user()->name,
        ];
    }
}
