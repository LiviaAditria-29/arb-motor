{{-- resources/views/admin/bookings/show.blade.php --}}
@extends('layouts.admin')
@section('title','Detail Booking #'.$booking->id)
@section('page-title','Detail Booking #'.$booking->id)
@section('breadcrumb')
    <span>/</span>
    <a href="{{ route('admin.bookings.index') }}" style="color:#64748B;text-decoration:none;">Booking</a>
    <span>/</span>
    <span style="color:#0F172A;">Detail #{{ $booking->id }}</span>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.25rem;max-width:1100px;">

    {{-- Left Column --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Pelanggan & Kendaraan --}}
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.95rem;">Informasi Pelanggan</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Nama</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->display_name }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">No. HP</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->customer_phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Kendaraan</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->display_vehicle }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Layanan</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->display_service }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Tanggal</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->booking_date->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Jam</p>
                    <p style="font-weight:600;color:#0F172A;">{{ substr($booking->time_slot,0,5) }}</p>
                </div>
            </div>
        </div>

        {{-- Teknis & Biaya --}}
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.95rem;">Detail Pengerjaan</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Teknisi</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->technician_name ?? '—' }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Selesai pada</p>
                    <p style="font-weight:600;color:#0F172A;">{{ $booking->completed_at ? $booking->completed_at->format('d M Y H:i') : '—' }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Estimasi Biaya</p>
                    <p style="font-weight:600;color:#64748B;">Rp {{ number_format($booking->estimated_cost ?? 0,0,',','.') }}</p>
                </div>
                <div>
                    <p class="f-label" style="margin-bottom:.25rem;">Biaya Aktual</p>
                    <p style="font-weight:700;color:#F97316;font-size:1.1rem;">Rp {{ number_format($booking->actual_cost ?? 0,0,',','.') }}</p>
                </div>
            </div>

            @if($booking->notes)
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #F1F5F9;">
                <p class="f-label" style="margin-bottom:.5rem;">Catatan</p>
                <p style="font-size:.875rem;color:#334155;line-height:1.6;background:#F8FAFC;border-radius:.75rem;padding:.875rem;">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Timeline / Audit --}}
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.95rem;">Timeline</p>
            <div style="position:relative;padding-left:1.5rem;">
                <div style="position:absolute;left:.5rem;top:0;bottom:0;width:2px;background:#F1F5F9;"></div>
                @php
                $timeline=[
                    ['label'=>'Booking dibuat','time'=>$booking->created_at?->format('d M Y H:i'),'icon'=>'📝','done'=>true],
                    ['label'=>'Dikonfirmasi','time'=>in_array($booking->status,['confirmed','in_progress','completed'])?'—':'—','icon'=>'✅','done'=>in_array($booking->status,['confirmed','in_progress','completed'])],
                    ['label'=>'Dalam pengerjaan','time'=>'—','icon'=>'🔧','done'=>in_array($booking->status,['in_progress','completed'])],
                    ['label'=>'Selesai','time'=>$booking->completed_at?->format('d M Y H:i')??'—','icon'=>'🏁','done'=>$booking->status==='completed'],
                ];
                @endphp
                @foreach($timeline as $t)
                <div style="display:flex;align-items:flex-start;gap:.875rem;margin-bottom:1.25rem;position:relative;">
                    <div style="width:24px;height:24px;border-radius:50%;background:{{ $t['done']?'#F97316':'#E2E8F0' }};display:flex;align-items:center;justify-content:center;font-size:.7rem;flex-shrink:0;margin-left:-.75rem;margin-top:.1rem;z-index:1;">{{ $t['done']?'✓':'' }}</div>
                    <div>
                        <p style="font-size:.845rem;font-weight:600;color:{{ $t['done']?'#0F172A':'#94A3B8' }};">{{ $t['label'] }}</p>
                        <p style="font-size:.72rem;color:#94A3B8;">{{ $t['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Status Card --}}
        <div class="card" style="text-align:center;">
            <p style="font-size:.72rem;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.625rem;">Status Saat Ini</p>
            <span class="badge badge-{{ $booking->status_label['color'] }}" style="font-size:.95rem;padding:.5rem 1.25rem;">{{ $booking->status_label['label'] }}</span>

            <div style="margin-top:1.25rem;border-top:1px solid #F1F5F9;padding-top:1.25rem;">
                <p style="font-size:.72rem;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.08em;margin-bottom:.625rem;">Update Status</p>
                <form method="POST" action="{{ route('admin.bookings.update-status',$booking->id) }}">
                    @csrf
                    <select name="status" class="f-input f-select" style="margin-bottom:.75rem;font-size:.82rem;">
                        @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','in_progress'=>'Diproses','completed'=>'Selesai','taken'=>'Diambil','cancelled'=>'Dibatalkan'] as $k=>$v)
                        <option value="{{ $k }}" {{ $booking->status===$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">Update Status</button>
                </form>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:.875rem;font-size:.875rem;">Aksi</p>
            <div style="display:flex;flex-direction:column;gap:.625rem;">
                <a href="{{ route('admin.bookings.edit',$booking->id) }}" class="btn btn-secondary" style="justify-content:center;">✏️ Edit Booking</a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$booking->customer_phone) }}?text=Halo%20{{ urlencode($booking->display_name) }},%20booking%20servis%20Anda%20pada%20{{ urlencode($booking->booking_date->format('d M Y')) }}" target="_blank" class="btn btn-success" style="justify-content:center;">💬 WhatsApp Pelanggan</a>
                <form id="del-bk" method="POST" action="{{ route('admin.bookings.destroy',$booking->id) }}">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDelete('del-bk','Booking #{{ $booking->id }}')" class="btn btn-danger" style="width:100%;justify-content:center;">🗑 Hapus Booking</button>
                </form>
            </div>
        </div>

        {{-- Meta --}}
        <div class="card" style="font-size:.78rem;color:#94A3B8;">
            <p style="margin-bottom:.5rem;">ID: <strong style="color:#334155;">#{{ $booking->id }}</strong></p>
            <p style="margin-bottom:.5rem;">Dibuat: <strong style="color:#334155;">{{ $booking->created_at?->format('d M Y H:i') ?? '-' }}</strong></p>
            <p>Diperbarui: <strong style="color:#334155;">{{ $booking->updated_at?->format('d M Y H:i') ?? '-' }}</strong></p>
        </div>

        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline" style="justify-content:center;">← Kembali ke Daftar</a>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
