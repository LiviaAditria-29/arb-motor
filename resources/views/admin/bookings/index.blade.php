{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kelola Booking')
@section('page-title','Kelola Booking')
@section('page-sub','Manajemen semua jadwal servis')

@section('breadcrumb')
<span>/</span> <span style="color:#0F172A;">Booking</span>
@endsection

@push('styles')
<style>
.filter-bar{background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;gap:.875rem;align-items:flex-end;}
.chip{padding:.3rem .85rem;border-radius:9999px;font-size:.75rem;font-weight:600;border:1.5px solid #E2E8F0;background:#fff;color:#64748B;cursor:pointer;text-decoration:none;transition:all .2s;}
.chip:hover,.chip.active{background:#0F172A;color:#fff;border-color:#0F172A;}
</style>
@endpush

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.875rem;">
    <div>
        <p style="font-size:.82rem;color:#64748B;">{{ $bookings->total() }} booking ditemukan</p>
    </div>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Booking
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" action="{{ route('admin.bookings.index') }}" id="filter-form">
<div class="filter-bar">
    {{-- Search --}}
    <div style="flex:1;min-width:200px;position:relative;">
        <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, kendaraan, layanan..."
               class="f-input" style="padding-left:2.25rem;" id="search-inp">
    </div>

    {{-- Date --}}
    <div>
        <input type="date" name="date" value="{{ request('date') }}" class="f-input" style="min-width:160px;" onchange="this.form.submit()">
    </div>

    {{-- Status chips --}}
    <div style="display:flex;flex-wrap:wrap;gap:.4rem;align-items:center;">
        <a href="{{ route('admin.bookings.index', array_filter(array_merge(request()->query(),['status'=>null]))) }}"
           class="chip {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Diproses','completed'=>'Selesai','cancelled'=>'Batal'] as $k=>$v)
        <a href="{{ route('admin.bookings.index', array_filter(array_merge(request()->query(),['status'=>$k]))) }}"
           class="chip {{ request('status')===$k ? 'active' : '' }}">{{ $v }}</a>
        @endforeach
    </div>

    @if(request()->hasAny(['search','status','date']))
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline btn-sm">✕ Reset</a>
    @endif
</div>
</form>

{{-- Table --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div style="overflow-x:auto;">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Layanan</th>
                    <th>Tanggal / Jam</th>
                    <th>Teknisi</th>
                    <th>Biaya</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td style="color:#94A3B8;font-size:.75rem;">{{ $b->id }}</td>
                    <td>
                        <p style="font-weight:600;font-size:.82rem;color:#0F172A;">{{ $b->display_name }}</p>
                        <p style="font-size:.72rem;color:#94A3B8;">{{ $b->customer_phone }}</p>
                    </td>
                    <td style="font-size:.82rem;">{{ $b->display_vehicle }}</td>
                    <td style="font-size:.82rem;">{{ $b->display_service }}</td>
                    <td>
                        <p style="font-size:.82rem;font-weight:500;">{{ $b->booking_date->format('d M Y') }}</p>
                        <p style="font-size:.7rem;color:#94A3B8;">{{ substr($b->time_slot,0,5) }}</p>
                    </td>
                    <td style="font-size:.82rem;color:#64748B;">{{ $b->technician_name ?? '-' }}</td>
                    <td>
                        <p style="font-size:.82rem;font-weight:600;color:#0F172A;">{{ $b->display_cost }}</p>
                        @if($b->actual_cost && $b->estimated_cost && $b->actual_cost !== $b->estimated_cost)
                        <p style="font-size:.7rem;color:#94A3B8;">Est: Rp {{ number_format($b->estimated_cost,0,',','.') }}</p>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ $b->status_label['color'] }}">{{ $b->status_label['label'] }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.3rem;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('admin.bookings.show',$b->id) }}" class="btn btn-outline btn-sm" title="Detail">👁</a>
                            <a href="{{ route('admin.bookings.edit',$b->id) }}" class="btn btn-secondary btn-sm" title="Edit">✏️</a>

                            {{-- Quick status update --}}
                            @if(in_array($b->status,['pending','confirmed']))
                            <form method="POST" action="{{ route('admin.bookings.update-status',$b->id) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="{{ $b->status==='pending'?'confirmed':'in_progress' }}">
                                <button type="submit" class="btn btn-success btn-sm" title="{{ $b->status==='pending'?'Konfirmasi':'Mulai Proses' }}">
                                    {{ $b->status==='pending'?'✓':'▶' }}
                                </button>
                            </form>
                            @endif

                            @if($b->status==='in_progress')
                            <form method="POST" action="{{ route('admin.bookings.update-status',$b->id) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success btn-sm" title="Selesaikan">✅</button>
                            </form>
                            @endif

                            <form id="del-{{ $b->id }}" method="POST" action="{{ route('admin.bookings.destroy',$b->id) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('del-{{ $b->id }}','Booking #{{ $b->id }}')" title="Hapus">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:4rem;color:#94A3B8;">
                        <div style="font-size:2.5rem;margin-bottom:.75rem;">📅</div>
                        <p style="font-weight:600;margin-bottom:.5rem;">Belum ada booking</p>
                        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm">+ Tambah Booking</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div style="padding:1rem 1.25rem;border-top:1px solid #F1F5F9;">
        {{ $bookings->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let dt;
document.getElementById('search-inp').addEventListener('input',function(){
    clearTimeout(dt);
    dt=setTimeout(()=>document.getElementById('filter-form').submit(),500);
});
</script>
@endpush
