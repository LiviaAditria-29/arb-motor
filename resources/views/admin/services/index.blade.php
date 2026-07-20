{{-- resources/views/admin/services/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kelola Layanan')
@section('page-title','Kelola Layanan')
@section('page-sub','Manajemen daftar layanan servis')
@section('breadcrumb') <span>/</span> <span style="color:#0F172A;">Layanan</span> @endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.875rem;">
    <p style="font-size:.82rem;color:#64748B;">{{ $services->total() }} layanan terdaftar</p>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
        <svg style="width:1rem;height:1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Layanan
    </a>
</div>

<div class="card" style="padding:0;overflow:hidden;">
    <table class="tbl">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Layanan</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Durasi</th>
                <th style="text-align:center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($services as $s)
            <tr>
                <td style="color:#94A3B8;font-size:.75rem;">{{ $s->id }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:.625rem;">
                        <div style="width:36px;height:36px;background:#FFF0E6;border-radius:.625rem;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">{{ $s->icon_emoji }}</div>
                        <p style="font-weight:600;font-size:.845rem;color:#0F172A;">{{ $s->name }}</p>
                    </div>
                </td>
                <td><span class="badge badge-orange">{{ $s->category }}</span></td>
                <td style="font-size:.8rem;color:#64748B;max-width:220px;">{{ Str::limit($s->description,70) }}</td>
                <td style="font-weight:600;font-size:.845rem;color:#0F172A;">{{ $s->formatted_price }}</td>
                <td style="font-size:.82rem;color:#64748B;">{{ $s->duration_minutes }} menit</td>
                <td>
                    <div style="display:flex;gap:.375rem;justify-content:center;">
                        <a href="{{ route('admin.services.edit',$s->id) }}" class="btn btn-secondary btn-sm">✏️ Edit</a>
                        <form id="ds-{{ $s->id }}" method="POST" action="{{ route('admin.services.destroy',$s->id) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete('ds-{{ $s->id }}','{{ addslashes($s->name) }}')" class="btn btn-danger btn-sm">🗑</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:4rem;color:#94A3B8;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">🔧</div>
                <p style="font-weight:600;margin-bottom:.5rem;">Belum ada layanan</p>
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary btn-sm">+ Tambah Layanan</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    @if($services->hasPages())
    <div style="padding:1rem 1.25rem;border-top:1px solid #F1F5F9;">{{ $services->links('vendor.pagination.tailwind') }}</div>
    @endif
</div>
@endsection

@push('scripts')<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>@endpush
