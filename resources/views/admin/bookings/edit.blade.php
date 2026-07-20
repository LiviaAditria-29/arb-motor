{{-- resources/views/admin/bookings/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Booking #'.$booking->id)
@section('page-title','Edit Booking #'.$booking->id)
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.bookings.index') }}" style="color:#64748B;text-decoration:none;">Booking</a> <span>/</span> <span style="color:#0F172A;">Edit</span> @endsection

@section('content')
<div style="max-width:800px;">
<form method="POST" action="{{ route('admin.bookings.update',$booking->id) }}">
@csrf @method('PUT')
<div style="display:flex;flex-direction:column;gap:1.25rem;">

    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Data Pelanggan & Kendaraan</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="f-label">Nama Pelanggan *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $booking->customer_name) }}" class="f-input" required>
            </div>
            <div>
                <label class="f-label">No. HP *</label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone', $booking->customer_phone) }}" class="f-input" required>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <label class="f-label">Kendaraan *</label>
            <input type="text" name="vehicle_name" value="{{ old('vehicle_name', $booking->vehicle_name) }}" class="f-input" required>
        </div>
    </div>

    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Jadwal, Layanan & Biaya</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="f-label">Layanan *</label>
                <select name="service_id" class="f-input f-select @error('service_id') err @enderror" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id', $booking->service_id) == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                    @endforeach
                </select>
                @error('service_id')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">Teknisi</label>
                <input type="text" name="technician_name" value="{{ old('technician_name', $booking->technician_name) }}" class="f-input">
            </div>
            <div>
                <label class="f-label">Tanggal *</label>
                <input type="date" name="booking_date" value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}" class="f-input" required>
            </div>
            <div>
                <label class="f-label">Jam *</label>
                <select name="time_slot" class="f-input f-select" required>
                    @foreach(['08:00:00','09:00:00','10:00:00','11:00:00','13:00:00','14:00:00','15:00:00','16:00:00'] as $t)
                    <option value="{{ $t }}" {{ old('time_slot', $booking->time_slot) === $t ? 'selected' : '' }}>{{ substr($t,0,5) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="f-label">Status *</label>
                <select name="status" class="f-input f-select">
                    @foreach($statuses as $s)
                <option value="{{ $s }}" {{ old('status', $booking->status) === $s ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_',' ', $s)) }}
                </option>
                @endforeach
                </select>
            </div>
            <div>
                <label class="f-label">Estimasi Biaya (Rp)</label>
                <input type="number" name="estimated_cost" value="{{ old('estimated_cost', $booking->estimated_cost) }}" class="f-input" min="0">
            </div>
            <div>
                <label class="f-label">Biaya Aktual (Rp)</label>
                <input type="number" name="actual_cost" value="{{ old('actual_cost', $booking->actual_cost) }}" class="f-input" min="0">
                <p style="font-size:.72rem;color:#94A3B8;margin-top:.2rem;">Isi setelah pekerjaan selesai</p>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <label class="f-label">Catatan</label>
            <textarea name="notes" rows="3" class="f-input" style="resize:vertical;">{{ old('notes', $booking->notes) }}</textarea>
        </div>
    </div>

    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
    </div>
</div>
</form>
</div>
@endsection