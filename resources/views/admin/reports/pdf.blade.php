<!DOCTYPE html>
{{-- resources/views/admin/reports/pdf.blade.php
     Template PDF rekap bulanan — dirender oleh barryvdh/laravel-dompdf
     PENTING: Gunakan inline style, hindari flexbox kompleks, gunakan table untuk layout --}}
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1E293B; background: #fff; }

    /* Header */
    .header { background: #0F172A; color: #fff; padding: 20px 28px; margin-bottom: 0; }
    .header-table { width: 100%; border-collapse: collapse; }
    .logo-box { background: #F97316; border-radius: 8px; width: 36px; height: 36px; text-align: center; font-size: 11px; font-weight: 800; color: #fff; line-height: 36px; display: inline-block; }
    .company-name { font-size: 18px; font-weight: 800; color: #fff; }
    .company-sub  { font-size: 9px; color: #94A3B8; margin-top: 2px; }
    .period-box   { text-align: right; }
    .period-label { font-size: 8px; color: #94A3B8; text-transform: uppercase; letter-spacing: 1px; }
    .period-val   { font-size: 15px; font-weight: 700; color: #F97316; margin-top: 3px; }

    /* Orange band */
    .band { background: #F97316; height: 4px; }

    /* Section */
    .section { padding: 16px 28px; }
    .section-title { font-size: 11px; font-weight: 700; color: #0F172A; border-left: 3px solid #F97316; padding-left: 8px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: .5px; }

    /* Stat cards via table */
    .stat-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .stat-cell { width: 25%; padding: 12px 10px; background: #F8FAFC; border: 1px solid #E2E8F0; text-align: center; }
    .stat-val { font-size: 16px; font-weight: 800; color: #0F172A; }
    .stat-lbl { font-size: 8px; color: #64748B; margin-top: 3px; text-transform: uppercase; letter-spacing: .5px; }
    .stat-orange .stat-val { color: #F97316; }

    /* Main data table */
    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .data-table th { background: #0F172A; color: #fff; padding: 7px 8px; font-size: 8px; text-align: left; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; }
    .data-table td { padding: 6px 8px; font-size: 9px; color: #334155; border-bottom: 1px solid #F1F5F9; vertical-align: top; }
    .data-table tr:nth-child(even) td { background: #FAFAFA; }
    .data-table tfoot td { background: #FFF0E6; font-weight: 700; font-size: 9px; border-top: 2px solid #F97316; }

    /* Service summary table */
    .svc-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
    .svc-table th { background: #334155; color: #fff; padding: 6px 8px; font-size: 8px; text-transform: uppercase; letter-spacing: .5px; }
    .svc-table td { padding: 6px 8px; font-size: 9px; border-bottom: 1px solid #F1F5F9; }
    .svc-table tfoot td { background: #FFF0E6; font-weight: 700; border-top: 2px solid #F97316; }

    /* Status badge */
    .badge { padding: 2px 6px; border-radius: 10px; font-size: 8px; font-weight: 700; }
    .badge-green  { background: #DCFCE7; color: #15803D; }
    .badge-yellow { background: #FEF3C7; color: #B45309; }
    .badge-blue   { background: #DBEAFE; color: #1D4ED8; }
    .badge-indigo { background: #EDE9FE; color: #6D28D9; }
    .badge-red    { background: #FEE2E2; color: #B91C1C; }
    .badge-gray   { background: #F1F5F9; color: #475569; }

    /* Footer */
    .footer { background: #F8FAFC; border-top: 1px solid #E2E8F0; padding: 12px 28px; margin-top: 8px; }
    .footer-table { width: 100%; border-collapse: collapse; }

    .page-break { page-break-before: always; }

    /* Divider */
    .divider { height: 1px; background: #E2E8F0; margin: 0 28px 16px; }
</style>
</head>
<body>

{{-- ═══════════════════════════════ HEADER ══════════════════════════════ --}}
<div class="header">
    <table class="header-table">
        <tr>
            <td style="width:60%;">
                <table style="border-collapse:collapse;">
                    <tr>
                        <td style="padding-right:12px;vertical-align:middle;">
                            <div class="logo-box">ARB</div>
                        </td>
                        <td style="vertical-align:middle;">
                            <div class="company-name">ARB Motor</div>
                            <div class="company-sub">Jl. Raya Motor No.123, Jakarta &nbsp;|&nbsp; +62 812-3456-7890 &nbsp;|&nbsp; info@arbmotor.com</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:40%;" class="period-box">
                <div class="period-label">Laporan Rekap Bulanan</div>
                <div class="period-val">{{ $data['month_name'] }}</div>
                <div class="company-sub" style="margin-top:4px;">Dicetak: {{ $data['generated_at'] }} &nbsp;|&nbsp; Oleh: {{ $data['generated_by'] }}</div>
            </td>
        </tr>
    </table>
</div>
<div class="band"></div>

{{-- ═══════════════════════════ RINGKASAN UTAMA ══════════════════════════ --}}
<div class="section">
    <div class="section-title">Ringkasan Performa</div>
    <table class="stat-table">
        <tr>
            <td class="stat-cell">
                <div class="stat-val">{{ $data['total_bookings'] }}</div>
                <div class="stat-lbl">Total Booking</div>
            </td>
            <td class="stat-cell">
                <div class="stat-val" style="color:#16A34A;">{{ $data['total_completed'] }}</div>
                <div class="stat-lbl">Selesai</div>
            </td>
            <td class="stat-cell">
                <div class="stat-val" style="color:#DC2626;">{{ $data['total_cancelled'] }}</div>
                <div class="stat-lbl">Dibatalkan</div>
            </td>
            <td class="stat-cell stat-orange">
                <div class="stat-val">Rp {{ number_format($data['total_revenue'],0,',','.') }}</div>
                <div class="stat-lbl">Total Pendapatan</div>
            </td>
        </tr>
        <tr>
            <td class="stat-cell">
                <div class="stat-val">{{ $data['total_pending'] }}</div>
                <div class="stat-lbl">Masih Proses</div>
            </td>
            <td class="stat-cell">
                @php $rate = $data['total_bookings'] > 0 ? round($data['total_completed']/$data['total_bookings']*100) : 0; @endphp
                <div class="stat-val" style="color:#16A34A;">{{ $rate }}%</div>
                <div class="stat-lbl">Completion Rate</div>
            </td>
            <td class="stat-cell stat-orange">
                <div class="stat-val">Rp {{ number_format($data['avg_revenue'],0,',','.') }}</div>
                <div class="stat-lbl">Rata-rata per Booking</div>
            </td>
            <td class="stat-cell">
                <div class="stat-val">{{ $data['bookings']->unique('display_name')->count() }}</div>
                <div class="stat-lbl">Pelanggan Unik</div>
            </td>
        </tr>
    </table>
</div>

{{-- ═══════════════════════════ REKAP PER LAYANAN ═══════════════════════ --}}
@if($data['by_service']->count())
<div class="divider"></div>
<div class="section">
    <div class="section-title">Rekap per Layanan</div>
    <table class="svc-table">
        <thead>
            <tr>
                <th>Layanan</th>
                <th style="text-align:center;">Total</th>
                <th style="text-align:center;">Selesai</th>
                <th style="text-align:center;">Dibatalkan</th>
                <th style="text-align:right;">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_service'] as $svc)
            <tr>
                <td style="font-weight:600;">{{ $svc['service'] }}</td>
                <td style="text-align:center;">{{ $svc['total'] }}</td>
                <td style="text-align:center;color:#16A34A;font-weight:600;">{{ $svc['selesai'] }}</td>
                <td style="text-align:center;color:#DC2626;">{{ $svc['batal'] }}</td>
                <td style="text-align:right;font-weight:600;">Rp {{ number_format($svc['revenue'],0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td style="font-weight:800;">TOTAL</td>
                <td style="text-align:center;font-weight:800;">{{ $data['total_bookings'] }}</td>
                <td style="text-align:center;font-weight:800;color:#16A34A;">{{ $data['total_completed'] }}</td>
                <td style="text-align:center;font-weight:800;color:#DC2626;">{{ $data['total_cancelled'] }}</td>
                <td style="text-align:right;font-weight:800;color:#F97316;">Rp {{ number_format($data['total_revenue'],0,',','.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>
@endif

{{-- ═══════════════════════ DAFTAR BOOKING DETAIL ════════════════════════ --}}
<div class="page-break"></div>
<div class="band"></div>
<div class="section" style="padding-top:20px;">
    <div class="section-title">Daftar Booking Detail — {{ $data['month_name'] }}</div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:9%;">Tanggal</th>
                <th style="width:7%;">Jam</th>
                <th style="width:18%;">Pelanggan</th>
                <th style="width:16%;">Kendaraan</th>
                <th style="width:18%;">Layanan</th>
                <th style="width:12%;">Teknisi</th>
                <th style="width:8%;text-align:center;">Status</th>
                <th style="width:8%;text-align:right;">Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data['bookings'] as $i => $b)
            @php
            $statusClass = match($b->status) {
                'completed'   => 'badge-green',
                'cancelled'   => 'badge-red',
                'confirmed'   => 'badge-blue',
                'in_progress' => 'badge-indigo',
                'pending'     => 'badge-yellow',
                default       => 'badge-gray',
            };
            @endphp
            <tr>
                <td style="color:#94A3B8;">{{ $i+1 }}</td>
                <td>{{ $b->booking_date->format('d/m/Y') }}</td>
                <td>{{ substr($b->time_slot,0,5) }}</td>
                <td>
                    <span style="font-weight:600;">{{ $b->display_name }}</span><br>
                    <span style="color:#94A3B8;font-size:8px;">{{ $b->customer_phone }}</span>
                </td>
                <td>{{ $b->display_vehicle }}</td>
                <td>{{ $b->display_service }}</td>
                <td style="color:#64748B;">{{ $b->technician_name ?? '—' }}</td>
                <td style="text-align:center;"><span class="badge {{ $statusClass }}">{{ $b->status_label['label'] }}</span></td>
                <td style="text-align:right;font-weight:600;">{{ $b->display_cost }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:24px;color:#94A3B8;">Tidak ada booking pada periode ini</td></tr>
            @endforelse
        </tbody>
        @if($data['bookings']->count())
        <tfoot>
            <tr>
                <td colspan="8" style="text-align:right;font-weight:800;">TOTAL PENDAPATAN</td>
                <td style="text-align:right;font-weight:800;color:#F97316;">Rp {{ number_format($data['total_revenue'],0,',','.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- ═══════════════════════════════ FOOTER ═════════════════════════════ --}}
<div class="footer">
    <table class="footer-table">
        <tr>
            <td style="font-size:8px;color:#94A3B8;">
                Dokumen ini digenerate secara otomatis oleh sistem ARB Motor.<br>
                Dicetak pada {{ $data['generated_at'] }} oleh {{ $data['generated_by'] }}
            </td>
            <td style="text-align:right;font-size:8px;color:#94A3B8;">
                ARB Motor &copy; {{ date('Y') }} &nbsp;|&nbsp; Semua hak dilindungi.<br>
                Halaman ini bersifat rahasia dan hanya untuk keperluan internal.
            </td>
        </tr>
    </table>
</div>

</body>
</html>
