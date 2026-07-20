{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')
@section('title','Laporan Bulanan')
@section('page-title','Laporan & Rekap Bulanan')
@section('page-sub','Ringkasan performa bengkel per bulan')
@section('breadcrumb') <span>/</span> <span style="color:#0F172A;">Laporan</span> @endsection

@push('styles')
<style>
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;}
@media(max-width:1024px){.grid-4{grid-template-columns:repeat(2,1fr);}.grid-2{grid-template-columns:1fr;}}
@media(max-width:640px){.grid-4{grid-template-columns:1fr;}}
.card{background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1.5rem;}
.tbl-zebra tbody tr:nth-child(even){background:#F8FAFC;}
</style>
@endpush

@section('content')

{{-- Selector & Actions --}}
<div style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1rem;margin-bottom:1.5rem;">

    <form method="GET" action="{{ route('admin.reports.index') }}" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
        <div>
            <label class="f-label" style="margin-bottom:.3rem;">Bulan</label>
            <select name="month" class="f-input f-select" style="min-width:140px;">
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i=>$mn)
                <option value="{{ $i+1 }}" {{ $month==($i+1)?'selected':'' }}>{{ $mn }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="f-label" style="margin-bottom:.3rem;">Tahun</label>
            <select name="year" class="f-input f-select" style="min-width:100px;">
                @foreach($availableYears->count() ? $availableYears : [date('Y')] as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">🔍 Tampilkan</button>
    </form>

    <div style="display:flex;gap:.625rem;flex-wrap:wrap;">
        <a href="{{ route('admin.reports.preview', ['month'=>$month,'year'=>$year]) }}" target="_blank" class="btn btn-secondary">
            👁 Preview PDF
        </a>
        <a href="{{ route('admin.reports.export', ['month'=>$month,'year'=>$year]) }}" class="btn btn-primary">
            ⬇️ Download PDF
        </a>
    </div>
</div>

{{-- Period Header --}}
<div class="card" style="background:linear-gradient(135deg,#0F172A,#1E293B);border:none;margin-bottom:1.5rem;padding:1.75rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
        <div>
            <p style="color:#94A3B8;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;">Periode Laporan</p>
            <p class="font-display" style="color:#fff;font-size:1.75rem;font-weight:700;margin-top:.25rem;">{{ $data['month_name'] }}</p>
            <p style="color:#64748B;font-size:.78rem;margin-top:.25rem;">Dibuat: {{ $data['generated_at'] }} oleh {{ $data['generated_by'] }}</p>
        </div>
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
            @foreach([['💰','Pendapatan','Rp '.number_format($data['total_revenue'],0,',','.')],['📅','Total Booking',$data['total_bookings']],['✅','Selesai',$data['total_completed']],['❌','Dibatalkan',$data['total_cancelled']]] as [$ico,$lbl,$val])
            <div style="text-align:center;">
                <p style="font-size:1.25rem;">{{ $ico }}</p>
                <p style="color:#F97316;font-weight:700;font-size:1.1rem;margin-top:.2rem;">{{ $val }}</p>
                <p style="color:#64748B;font-size:.72rem;">{{ $lbl }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid-4" style="margin-bottom:1.5rem;">
    @php
    $cards2=[
        ['label'=>'Total Booking','val'=>$data['total_bookings'],'icon'=>'📅','bg'=>'#FFF0E6','c'=>'#F97316'],
        ['label'=>'Booking Selesai','val'=>$data['total_completed'],'icon'=>'✅','bg'=>'#DCFCE7','c'=>'#16A34A'],
        ['label'=>'Total Pendapatan','val'=>'Rp '.number_format($data['total_revenue'],0,',','.'),'icon'=>'💰','bg'=>'#EDE9FE','c'=>'#7C3AED'],
        ['label'=>'Rata-rata per Booking','val'=>'Rp '.number_format($data['avg_revenue'],0,',','.'),'icon'=>'📊','bg'=>'#DBEAFE','c'=>'#2563EB'],
    ];
    @endphp
    @foreach($cards2 as $c)
    <div class="card">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $c['bg'] }};display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:.875rem;">{{ $c['icon'] }}</div>
        <p class="font-display" style="font-size:1.5rem;font-weight:700;color:#0F172A;line-height:1;">{{ $c['val'] }}</p>
        <p style="font-size:.78rem;color:#64748B;margin-top:.35rem;">{{ $c['label'] }}</p>
    </div>
    @endforeach
</div>

<div class="grid-2" style="margin-bottom:1.5rem;">

    {{-- Chart Harian --}}
    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">
            Booking per Hari
        </p>

        @if($data['by_day']->count())
            <div style="height:300px;">
                <canvas id="chart-daily"></canvas>
            </div>
        @else
            <div style="text-align:center;padding:3rem;color:#94A3B8;">
                Tidak ada data untuk periode ini
            </div>
        @endif
    </div>

    {{-- Rekap per Status --}}
    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Distribusi Status</p>
        @php
        $statusMap=['pending'=>['Menunggu','badge-yellow'],'confirmed'=>['Dikonfirmasi','badge-blue'],'in_progress'=>['Diproses','badge-indigo'],'completed'=>['Selesai','badge-green'],'cancelled'=>['Dibatalkan','badge-red']];
        $totalSt=$data['total_bookings'];
        @endphp
        <div style="display:flex;flex-direction:column;gap:.875rem;">
            @foreach($statusMap as $key=>[$lbl,$badge])
            @php $cnt=$data['by_status'][$key]??0; $pct=$totalSt>0?round($cnt/$totalSt*100):0; @endphp
            <div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.35rem;">
                    <span class="badge {{ $badge }}">{{ $lbl }}</span>
                    <span style="font-size:.82rem;font-weight:700;color:#0F172A;">{{ $cnt }} <span style="color:#94A3B8;font-weight:400;">({{ $pct }}%)</span></span>
                </div>
                <div style="height:6px;background:#F1F5F9;border-radius:9999px;overflow:hidden;">
                    <div style="height:100%;width:{{ $pct }}%;border-radius:9999px;background:{{ match($key){'pending'=>'#F59E0B','confirmed'=>'#3B82F6','in_progress'=>'#8B5CF6','completed'=>'#22C55E',default=>'#EF4444'} }};transition:width .6s;"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Rekap per Layanan --}}
@if($data['by_service']->count())
<div class="card" style="margin-bottom:1.5rem;">
    <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Rekap per Layanan</p>
    <div style="overflow-x:auto;">
        <table class="tbl tbl-zebra" style="min-width:600px;">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th style="text-align:center;">Total Booking</th>
                    <th style="text-align:center;">Selesai</th>
                    <th style="text-align:center;">Dibatalkan</th>
                    <th style="text-align:right;">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['by_service'] as $svc)
                <tr>
                    <td style="font-weight:600;font-size:.845rem;">{{ $svc['service'] }}</td>
                    <td style="text-align:center;"><span class="badge badge-gray">{{ $svc['total'] }}</span></td>
                    <td style="text-align:center;"><span class="badge badge-green">{{ $svc['selesai'] }}</span></td>
                    <td style="text-align:center;"><span class="badge badge-red">{{ $svc['batal'] }}</span></td>
                    <td style="text-align:right;font-weight:700;color:#0F172A;">Rp {{ number_format($svc['revenue'],0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background:#F8FAFC;">
                    <td style="font-weight:700;font-size:.845rem;color:#0F172A;">TOTAL</td>
                    <td style="text-align:center;font-weight:700;">{{ $data['total_bookings'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#16A34A;">{{ $data['total_completed'] }}</td>
                    <td style="text-align:center;font-weight:700;color:#DC2626;">{{ $data['total_cancelled'] }}</td>
                    <td style="text-align:right;font-weight:700;color:#F97316;font-size:.95rem;">Rp {{ number_format($data['total_revenue'],0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endif

{{-- Daftar Booking Detail --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem;">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;">Daftar Booking — {{ $data['month_name'] }}</p>
        <p style="font-size:.78rem;color:#94A3B8;">{{ $data['bookings']->count() }} transaksi</p>
    </div>
    <div style="overflow-x:auto;">
        <table class="tbl tbl-zebra" style="min-width:700px;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Layanan</th>
                    <th>Status</th>
                    <th style="text-align:right;">Biaya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['bookings'] as $i=>$b)
                <tr>
                    <td style="color:#94A3B8;font-size:.75rem;">{{ $i+1 }}</td>
                    <td>
                        <p style="font-size:.82rem;font-weight:500;">{{ $b->booking_date->format('d M Y') }}</p>
                        <p style="font-size:.7rem;color:#94A3B8;">{{ substr($b->time_slot,0,5) }}</p>
                    </td>
                    <td>
                        <p style="font-size:.82rem;font-weight:600;color:#0F172A;">{{ $b->display_name }}</p>
                        <p style="font-size:.7rem;color:#94A3B8;">{{ $b->customer_phone }}</p>
                    </td>
                    <td style="font-size:.82rem;">{{ $b->display_vehicle }}</td>
                    <td style="font-size:.82rem;">{{ $b->display_service }}</td>
                    <td><span class="badge badge-{{ $b->status_label['color'] }}">{{ $b->status_label['label'] }}</span></td>
                    <td style="text-align:right;font-weight:600;font-size:.845rem;color:#0F172A;">{{ $b->display_cost }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:#94A3B8;">Tidak ada booking pada periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

@if($data['by_day']->count())
<script>
document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('chart-daily');

    if (!ctx) return;

    const dayLabels = @json($data['by_day']->pluck('date')->values());
    const dayTotal  = @json($data['by_day']->pluck('total')->values());

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Booking',
                data: dayTotal,
                backgroundColor: 'rgba(249,115,22,0.2)',
                borderColor: '#F97316',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

});
</script>
@endif
@endpush