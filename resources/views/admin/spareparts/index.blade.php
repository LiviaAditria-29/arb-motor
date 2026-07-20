{{-- resources/views/admin/spareparts/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Kelola Spare Part')
@section('page-title', 'Kelola Spare Part')
@section('page-subtitle', 'Manajemen stok dan data spare part')

@push('styles')
<style>
.part-img { width:48px; height:48px; border-radius:.75rem; object-fit:cover; background:#F1F5F9; }
.part-img-ph { width:48px; height:48px; border-radius:.75rem; background:#F1F5F9; display:flex; align-items:center; justify-content:center; color:#CBD5E1; font-size:1.25rem; }
.table-row { transition:background .15s; }
.table-row:hover { background:#FFFBF5; }
.stock-ok  { background:#DCFCE7; color:#16A34A; }
.stock-low { background:#FEF3C7; color:#D97706; }
.stock-out { background:#FEE2E2; color:#DC2626; }
.badge { display:inline-flex; align-items:center; padding:.2rem .65rem; border-radius:9999px; font-size:.7rem; font-weight:700; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-7">
    <div>
        <p class="text-slate-400 text-sm">{{ $spareParts->total() }} spare part terdaftar</p>
    </div>
    <a href="{{ route('admin.spare-parts.create') }}" class="btn-orange px-5 py-2.5">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Spare Part
    </a>
</div>

{{-- Desktop Table --}}
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hidden md:block">
    <table class="w-full">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Produk</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                <th class="text-left px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Merek</th>
                <th class="text-center px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($spareParts as $part)
            <tr class="table-row" id="row-{{ $part->id }}">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($part->image)
                            <img src="{{ asset('storage/'.$part->image) }}" alt="{{ $part->name }}" class="part-img">
                        @else
                            <div class="part-img-ph">⚙️</div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-slate-900 max-w-[220px] truncate">{{ $part->name }}</p>
                            <p class="text-xs text-slate-400">{{ $part->unit }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($part->category)
                    <span class="badge" style="background:#FFF0E6;color:#F97316;">{{ $part->category }}</span>
                    @else<span class="text-slate-300 text-sm">-</span>@endif
                </td>
                <td class="px-6 py-4 text-sm font-semibold text-slate-900">{{ $part->formatted_price }}</td>
                <td class="px-6 py-4">
                    @php $sc = $part->stock===0?'stock-out':($part->stock<=3?'stock-low':'stock-ok'); @endphp
                    <span class="badge {{ $sc }}">{{ $part->stock }} {{ $part->unit }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $part->brand ?? '-' }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('spare-parts.show', $part->id) }}" target="_blank"
                           title="Preview" class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.spare-parts.edit', $part->id) }}"
                           title="Edit" class="p-1.5 text-blue-500 hover:text-blue-700 rounded-lg hover:bg-blue-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <button onclick="confirmDelete({{ $part->id }}, '{{ addslashes($part->name) }}')"
                                title="Hapus" class="p-1.5 text-red-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                        <form id="delete-form-{{ $part->id }}" method="POST" action="{{ route('admin.spare-parts.destroy', $part->id) }}" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-16 text-slate-400">
                    <div class="text-4xl mb-3">⚙️</div>
                    <p class="font-medium">Belum ada spare part</p>
                    <a href="{{ route('admin.spare-parts.create') }}" class="btn-orange mt-4 inline-flex px-5 py-2 text-sm">+ Tambah Pertama</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($spareParts->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $spareParts->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

{{-- Mobile Cards --}}
<div class="md:hidden space-y-3">
    @forelse($spareParts as $part)
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center gap-4 p-4 border-b border-slate-100">
            @if($part->image)
                <img src="{{ asset('storage/'.$part->image) }}" alt="{{ $part->name }}" class="w-14 h-14 rounded-xl object-cover">
            @else
                <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center text-2xl">⚙️</div>
            @endif
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-slate-900 text-sm truncate">{{ $part->name }}</h3>
                <p class="text-xs text-slate-400">{{ $part->brand ?? 'No brand' }}</p>
                <p class="font-bold text-orange-500 text-sm mt-0.5">{{ $part->formatted_price }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between px-4 py-3">
            @php $sc = $part->stock===0?'stock-out':($part->stock<=3?'stock-low':'stock-ok'); @endphp
            <span class="badge {{ $sc }}">Stok: {{ $part->stock }}</span>
            <div class="flex gap-2">
                <a href="{{ route('admin.spare-parts.edit', $part->id) }}" class="btn-secondary text-xs px-3 py-1.5">Edit</a>
                <button onclick="confirmDelete({{ $part->id }}, '{{ addslashes($part->name) }}')" class="btn-danger text-xs px-3 py-1.5">Hapus</button>
                <form id="delete-form-{{ $part->id }}" method="POST" action="{{ route('admin.spare-parts.destroy', $part->id) }}" class="hidden">@csrf @method('DELETE')</form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-slate-400">Belum ada data spare part</div>
    @endforelse
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Hapus Spare Part?',
        html: `Yakin ingin menghapus <strong>${name}</strong>?<br><span style="color:#94A3B8;font-size:.85rem">Tindakan ini tidak dapat dibatalkan.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#94A3B8',
        borderRadius: '16px',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl px-5 py-2.5',
            cancelButton: 'rounded-xl px-5 py-2.5',
        }
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>
@endpush
