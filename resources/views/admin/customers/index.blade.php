{{-- resources/views/admin/customers/index.blade.php --}}
@extends('layouts.admin')
@section('title','Data Pelanggan')
@section('page-title','Data Pelanggan')
@section('page-sub','Seluruh pelanggan yang terdaftar di sistem')
@section('breadcrumb') <span>/</span> <span style="color:#0F172A;">Pelanggan</span> @endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.875rem;">
    <p style="font-size:.82rem;color:#64748B;">{{ $customers->total() }} pelanggan terdaftar</p>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.customers.index') }}" id="cust-form">
<div style="background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1rem 1.25rem;margin-bottom:1.25rem;">
    <div style="position:relative;max-width:400px;">
        <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" id="cust-search" value="{{ request('search') }}" placeholder="Cari nama atau no. HP..." class="f-input" style="padding-left:2.25rem;">
    </div>
</div>
</form>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pelanggan</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Kendaraan</th>
                <th>Total Booking</th>
                <th>Bergabung</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $c)
            <tr>
                <td style="color:#94A3B8;font-size:.75rem;">{{ $c->id }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:.625rem;">
                        <div style="width:34px;height:34px;background:#FFF0E6;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#F97316;font-weight:700;font-size:.82rem;flex-shrink:0;">{{ strtoupper(substr($c->name,0,1)) }}</div>
                        <p style="font-weight:600;font-size:.845rem;color:#0F172A;">{{ $c->name }}</p>
                    </div>
                </td>
                <td style="font-size:.82rem;">{{ $c->phone }}</td>
                <td style="font-size:.8rem;color:#64748B;max-width:180px;">{{ $c->address ? Str::limit($c->address,40) : '—' }}</td>
                <td>
                    <span class="badge badge-blue" style="font-size:.68rem;">{{ $c->vehicles_count ?? $c->vehicles->count() }} kendaraan</span>
                </td>
                <td>
                    <span class="badge badge-orange">{{ $c->bookings_count }} booking</span>
                </td>
                <td style="font-size:.78rem;color:#94A3B8;">{{ $c->created_at?->format('d M Y') ?? '—' }}</td>
                <td style="text-align:center;">
                    <a href="{{ route('admin.customers.show',$c->id) }}" class="btn btn-outline btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:4rem;color:#94A3B8;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">👥</div>
                <p style="font-weight:600;">Belum ada pelanggan</p>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    @if($customers->hasPages())
    <div style="padding:1rem 1.25rem;border-top:1px solid #F1F5F9;">{{ $customers->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let dt;
document.getElementById('cust-search').addEventListener('input',function(){clearTimeout(dt);dt=setTimeout(()=>document.getElementById('cust-form').submit(),500);});
</script>
@endpush
