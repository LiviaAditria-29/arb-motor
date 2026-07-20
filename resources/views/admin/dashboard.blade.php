{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Ringkasan aktivitas bengkel hari ini')

@push('styles')
<style>
.grid-4{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;}
.grid-2{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;}
@media(max-width:1280px){.grid-4{grid-template-columns:repeat(2,1fr);}}
@media(max-width:768px){.grid-4,.grid-3,.grid-2{grid-template-columns:1fr;}}
.card{background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1.5rem;}
.stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;}
.section-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1rem;color:#0F172A;margin-bottom:1.25rem;}
</style>
@endpush

@section('content')

{{-- ── Stat Cards ── --}}
<div class="grid-4" style="margin-bottom:1.25rem;">
    @php
    $cards=[
        ['icon'=>'📅','label'=>'Booking Bulan Ini','value'=>$stats['booking_this_month'],'sub'=>'Total: '.$stats['total_bookings'],'bg'=>'background:#FFF0E6;','color'=>'color:#F97316;'],
        ['icon'=>'⏳','label'=>'Menunggu Konfirmasi','value'=>$stats['pending'],'sub'=>'Perlu tindakan segera','bg'=>'background:#FEF3C7;','color'=>'color:#D97706;'],
        ['icon'=>'✅','label'=>'Selesai','value'=>$stats['completed'],'sub'=>'Total booking selesai','bg'=>'background:#DCFCE7;','color'=>'color:#16A34A;'],
        ['icon'=>'💰','label'=>'Pendapatan Bulan Ini','value'=>'Rp '.number_format($stats['revenue_this_month'],0,',','.'),'sub'=>'Dari booking selesai','bg'=>'background:#EDE9FE;','color'=>'color:#7C3AED;'],
    ];
    @endphp
    @foreach($cards as $c)
    <div class="card" style="transition:all .25s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(15,23,42,.08)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem;">
            <div class="stat-icon" style="{{ $c['bg'] }}">{{ $c['icon'] }}</div>
        </div>
        <p class="font-display" style="font-size:1.6rem;font-weight:700;color:#0F172A;line-height:1;">{{ $c['value'] }}</p>
        <p style="font-size:.78rem;font-weight:600;color:#475569;margin-top:.35rem;">{{ $c['label'] }}</p>
        <p style="font-size:.72rem;color:#94A3B8;margin-top:.2rem;">{{ $c['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- Row 2: Mini stats --}}
<div class="grid-3" style="margin-bottom:1.25rem;">
    <div class="card" style="display:flex;align-items:center;gap:1rem;">
        <div class="stat-icon" style="background:#EFF6FF;">👥</div>
        <div><p class="font-display" style="font-size:1.5rem;font-weight:700;color:#0F172A;">{{ $stats['total_customers'] }}</p><p style="font-size:.78rem;color:#64748B;">Total Pelanggan</p></div>
    </div>
    <div class="card" style="display:flex;align-items:center;gap:1rem;">
        <div class="stat-icon" style="background:#F0FDF4;">⚙️</div>
        <div><p class="font-display" style="font-size:1.5rem;font-weight:700;color:#0F172A;">{{ $stats['total_spare_parts'] }}</p><p style="font-size:.78rem;color:#64748B;">Spare Part</p>
        @if($stats['low_stock']>0)<p style="font-size:.7rem;color:#D97706;margin-top:.2rem;">⚠ {{ $stats['low_stock'] }} stok menipis</p>@endif
        @if($stats['out_of_stock']>0)<p style="font-size:.7rem;color:#DC2626;">✕ {{ $stats['out_of_stock'] }} habis</p>@endif
        </div>
    </div>
    <div class="card" style="display:flex;align-items:center;gap:1rem;">
        <div class="stat-icon" style="background:#FFF0E6;">🔧</div>
        <div><p class="font-display" style="font-size:1.5rem;font-weight:700;color:#0F172A;">{{ $stats['total_services'] }}</p><p style="font-size:.78rem;color:#64748B;">Jenis Layanan</p></div>
    </div>
</div>

{{-- Row 3: Chart + Sidebar --}}
<div class="grid-2" style="margin-bottom:1.25rem;">

    {{-- Chart --}}
    <div class="card" style="grid-column:span 1;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <p class="section-title" style="margin:0;">Booking Bulanan {{ date('Y') }}</p>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">Lihat Laporan →</a>
        </div>
        <div style="position:relative;height:260px;width:100%;">
            <canvas id="chart-booking"></canvas>
        </div>
    </div>

    {{-- Status Breakdown + Top Services --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Status --}}
        <div class="card" style="flex:1;">
            <p class="section-title">Status Booking</p>
            @php
            $statusMap=['pending'=>['Menunggu','badge-yellow'],'confirmed'=>['Dikonfirmasi','badge-blue'],'in_progress'=>['Diproses','badge-indigo'],'completed'=>['Selesai','badge-green'],'cancelled'=>['Dibatalkan','badge-red']];
            $total=array_sum($statusBreakdown->toArray());
            @endphp
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($statusMap as $key=>[$label,$badge])
                @php $count=$statusBreakdown[$key]??0; $pct=$total>0?round($count/$total*100):0; @endphp
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.25rem;">
                        <span class="badge {{ $badge }}" style="font-size:.68rem;">{{ $label }}</span>
                        <span style="font-size:.78rem;font-weight:600;color:#334155;">{{ $count }} <span style="color:#94A3B8;font-weight:400;">({{ $pct }}%)</span></span>
                    </div>
                    <div style="height:4px;background:#F1F5F9;border-radius:9999px;overflow:hidden;">
                        <div style="height:100%;width:{{ $pct }}%;background:{{ match($key){'pending'=>'#F59E0B','confirmed'=>'#3B82F6','in_progress'=>'#8B5CF6','completed'=>'#22C55E',default=>'#EF4444'} }};border-radius:9999px;transition:width .5s;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Top Services --}}
        @if($topServices->count()>0)
        <div class="card" style="flex:1;">
            <p class="section-title">Layanan Terpopuler</p>
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($topServices as $i=>$svc)
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:.625rem;">
                        <div style="width:22px;height:22px;background:{{ $i===0?'#F97316':($i===1?'#6366F1':'#94A3B8') }};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0;">{{ $i+1 }}</div>
                        <p style="font-size:.8rem;color:#334155;font-weight:500;">{{ $svc->service_name }}</p>
                    </div>
                    <span style="font-size:.75rem;font-weight:700;color:#0F172A;">{{ $svc->total }}x</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Row 4: Recent Bookings --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
        <p class="section-title" style="margin:0;">Booking Terbaru</p>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">Lihat Semua →</a>
    </div>

    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Layanan</th>
                    <th>Tanggal & Jam</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.625rem;">
                            <div style="width:32px;height:32px;background:#FFF0E6;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#F97316;font-weight:700;font-size:.75rem;flex-shrink:0;">{{ strtoupper(substr($b->display_name,0,1)) }}</div>
                            <div>
                                <p style="font-size:.82rem;font-weight:600;color:#0F172A;">{{ $b->display_name }}</p>
                                <p style="font-size:.7rem;color:#94A3B8;">{{ $b->customer_phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.82rem;">{{ $b->display_vehicle }}</td>
                    <td style="font-size:.82rem;">{{ $b->display_service }}</td>
                    <td>
                        <p style="font-size:.82rem;font-weight:500;color:#334155;">{{ $b->booking_date->format('d M Y') }}</p>
                        <p style="font-size:.7rem;color:#94A3B8;">{{ substr($b->time_slot,0,5) }}</p>
                    </td>
                    <td style="font-size:.82rem;font-weight:600;color:#0F172A;">{{ $b->display_cost }}</td>
                    <td><span class="badge badge-{{ $b->status_label['color'] }}">{{ $b->status_label['label'] }}</span></td>
                    <td>
                        <div style="display:flex;gap:.35rem;">
                            <a href="{{ route('admin.bookings.show',$b->id) }}" class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('admin.bookings.edit',$b->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:#94A3B8;">Belum ada booking</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('chart-booking');
    if (!canvas) return;

    if (window._dashChart instanceof Chart) {
        window._dashChart.destroy();
        window._dashChart = null;
    }

    window._dashChart = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'Booking',
                    data: {!! json_encode($chartBooking) !!},
                    backgroundColor: 'rgba(249,115,22,.2)',
                    borderColor: '#F97316',
                    borderWidth: 2,
                    borderRadius: 6,
                    order: 2
                },
                {
                    label: 'Pendapatan (÷1000)',
                    data: {!! json_encode(array_map(fn($v) => round($v / 1000), $chartRevenue)) !!},
                    type: 'line',
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139,92,246,.08)',
                    borderWidth: 2,
                    tension: .4,
                    pointRadius: 4,
                    fill: true,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { font: { size: 11 }, boxWidth: 12 }
                },
                tooltip: {
                    backgroundColor: '#0F172A',
                    padding: 10,
                    callbacks: {
                        label: c => c.datasetIndex === 1
                            ? ' Rp ' + (c.parsed.y * 1000).toLocaleString('id-ID')
                            : ' ' + c.parsed.y + ' booking'
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 11 }, color: '#94A3B8' }
                },
                y: {
                    grid: { color: '#F1F5F9' },
                    border: { display: false },
                    ticks: { font: { size: 11 }, color: '#94A3B8' },
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
