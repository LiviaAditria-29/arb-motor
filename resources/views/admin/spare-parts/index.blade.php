{{-- resources/views/admin/spare-parts/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kelola Spare Part')
@section('page-title','Kelola Spare Part')
@section('page-sub','Manajemen stok dan data spare part')
@section('breadcrumb') <span>/</span> <span style="color:#0F172A;">Spare Part</span> @endsection

@push('styles')
<style>
.sp-img{width:44px;height:44px;object-fit:cover;border-radius:.625rem;background:#F1F5F9;}
.sp-placeholder{width:44px;height:44px;border-radius:.625rem;background:linear-gradient(135deg,#F1F5F9,#E2E8F0);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
</style>
@endpush

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.875rem;">
    <p style="font-size:.82rem;color:#64748B;">{{ $spareParts->total() }} spare part terdaftar</p>
    <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Spare Part
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('admin.spare-parts.index') }}" id="sp-form">
<div style="background:#fff;border:1px solid #E2E8F0;border-radius:1rem;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;gap:.75rem;align-items:center;">
    <div style="flex:1;min-width:200px;position:relative;">
        <svg style="position:absolute;left:.75rem;top:50%;transform:translateY(-50%);width:1rem;height:1rem;color:#94A3B8;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" name="search" id="sp-search" value="{{ request('search') }}" placeholder="Cari nama, merek..." class="f-input" style="padding-left:2.25rem;">
    </div>
    @if($categories->count())
    <select name="category" class="f-input f-select" style="min-width:160px;" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $c)
        <option value="{{ $c }}" {{ request('category')===$c?'selected':'' }}>{{ $c }}</option>
        @endforeach
    </select>
    @endif
    @if(request()->hasAny(['search','category']))
    <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-outline btn-sm">✕ Reset</a>
    @endif
</div>
</form>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="tbl">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Merek</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($spareParts as $p)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:.75rem;">
                        @if($p->image)
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="sp-img">
                        @else
                            <div class="sp-placeholder">⚙️</div>
                        @endif
                        <div>
                            <p style="font-weight:600;font-size:.845rem;color:#0F172A;max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $p->name }}</p>
                            @if($p->compatible_vehicles)
                            <p style="font-size:.7rem;color:#94A3B8;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;">{{ Str::limit($p->compatible_vehicles,40) }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if($p->category)
                    <span class="badge badge-orange" style="font-size:.68rem;">{{ $p->category }}</span>
                    @else<span style="color:#94A3B8;font-size:.8rem;">—</span>@endif
                </td>
                <td style="font-size:.82rem;color:#64748B;">{{ $p->brand ?? '—' }}</td>
                <td style="font-weight:600;font-size:.845rem;color:#0F172A;">{{ $p->formatted_price }}</td>
                <td>
                    @php $sc = $p->stock===0?'badge-red':($p->stock<=3?'badge-yellow':'badge-green'); @endphp
                    <span class="badge {{ $sc }}">{{ $p->stock }}</span>
                </td>
                <td style="font-size:.82rem;color:#64748B;">{{ $p->unit }}</td>
                <td>
                    <div style="display:flex;gap:.375rem;justify-content:center;">
                        <a href="{{ route('admin.spare-parts.edit',$p->id) }}" class="btn btn-secondary btn-sm">✏️</a>
                        <form id="dsp-{{ $p->id }}" method="POST" action="{{ route('admin.spare-parts.destroy',$p->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('dsp-{{ $p->id }}','{{ addslashes($p->name) }}')" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:4rem;color:#94A3B8;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">⚙️</div>
                <p style="font-weight:600;">Belum ada spare part</p>
                <a href="{{ route('admin.spare-parts.create') }}" class="btn btn-primary btn-sm" style="margin-top:.75rem;">+ Tambah Pertama</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    @if($spareParts->hasPages())
    <div style="padding:1rem 1.25rem;border-top:1px solid #F1F5F9;">{{ $spareParts->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let dt;
document.getElementById('sp-search').addEventListener('input',function(){clearTimeout(dt);dt=setTimeout(()=>document.getElementById('sp-form').submit(),500);});
</script>
@endpush
