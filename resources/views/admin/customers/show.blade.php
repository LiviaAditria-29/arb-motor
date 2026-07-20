{{-- resources/views/admin/customers/show.blade.php --}}
@extends('layouts.admin')
@section('title','Detail Pelanggan')
@section('page-title','Detail Pelanggan')
@section('breadcrumb')
    <span>/</span>
    <a href="{{ route('admin.customers.index') }}" style="color:#64748B;text-decoration:none;">Pelanggan</a>
    <span>/</span>
    <span style="color:#0F172A;">{{ $customer->name }}</span>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;max-width:1100px;">

    {{-- Profile Card --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="card" style="text-align:center;">
            <div style="width:64px;height:64px;background:#FFF0E6;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#F97316;font-weight:800;font-size:1.5rem;margin:0 auto 1rem;">
                {{ strtoupper(substr($customer->name,0,1)) }}
            </div>
            <p style="font-weight:700;font-size:1.1rem;color:#0F172A;">{{ $customer->name }}</p>
            <p style="font-size:.82rem;color:#94A3B8;margin-top:.25rem;">{{ $customer->phone }}</p>
            @if($customer->address)
            <p style="font-size:.78rem;color:#64748B;margin-top:.5rem;line-height:1.5;">{{ $customer->address }}</p>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #F1F5F9;">
                <div>
                    <p style="font-size:1.25rem;font-weight:700;color:#F97316;">{{ $customer->bookings->count() }}</p>
                    <p style="font-size:.72rem;color:#94A3B8;">Total Booking</p>
                </div>
                <div>
                    <p style="font-size:1.25rem;font-weight:700;color:#0F172A;">{{ $customer->vehicles->count() }}</p>
                    <p style="font-size:.72rem;color:#94A3B8;">Kendaraan</p>
                </div>
            </div>
        </div>

        {{-- Kendaraan --}}
        @if($customer->vehicles->count())
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:.875rem;font-size:.875rem;">Kendaraan</p>
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                @foreach($customer->vehicles as $v)
                <div style="background:#F8FAFC;border-radius:.75rem;padding:.75rem;">
                    <p style="font-weight:600;font-size:.845rem;color:#0F172A;">{{ $v->plate_number }}</p>
                    <p style="font-size:.78rem;color:#64748B;margin-top:.2rem;">{{ $v->brand }} {{ $v->model }} {{ $v->year }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline" style="justify-content:center;">← Kembali</a>
    </div>

    {{-- Booking History --}}
    <div class="card" style="padding:0;overflow:hidden;">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #F1F5F9;">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;">Riwayat Booking</p>
        </div>
        <div style="overflow-x:auto;">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Layanan</th>
                        <th>Kendaraan</th>
                        <th>Teknisi</th>
                        <th>Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->bookings as $b)
                    <tr>
                        <td>
                            <p style="font-size:.82rem;font-weight:500;">{{ $b->booking_date->format('d M Y') }}</p>
                            <p style="font-size:.72rem;color:#94A3B8;">{{ substr($b->time_slot,0,5) }}</p>
                        </td>
                        <td style="font-size:.82rem;">{{ $b->display_service }}</td>
                        <td style="font-size:.82rem;">{{ $b->display_vehicle }}</td>
                        <td style="font-size:.82rem;color:#64748B;">{{ $b->technician_name ?? '—' }}</td>
                        <td style="font-size:.82rem;font-weight:600;">{{ $b->display_cost }}</td>
                        <td><span class="badge badge-{{ $b->status_label['color'] }}">{{ $b->status_label['label'] }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;">Belum ada riwayat booking</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
