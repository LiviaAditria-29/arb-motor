{{-- resources/views/admin/bookings/create.blade.php --}}
@extends('layouts.admin')
@section('title','Tambah Booking')
@section('page-title','Tambah Booking')
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.bookings.index') }}" style="color:#64748B;text-decoration:none;">Booking</a> <span>/</span> <span style="color:#0F172A;">Tambah</span> @endsection

@section('content')
<div style="max-width:800px;">
<form method="POST" action="{{ route('admin.bookings.store') }}">
@csrf
<div style="display:flex;flex-direction:column;gap:1.25rem;">

    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Data Pelanggan & Kendaraan</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="f-label">Nama Pelanggan *</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="f-input @error('customer_name') err @enderror" required>
                @error('customer_name')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">No. HP / WhatsApp *</label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="f-input @error('customer_phone') err @enderror" required>
                @error('customer_phone')<p class="f-error">{{ $message }}</p>@enderror
            </div>
        </div>
        <div style="margin-top:1rem;">
            <label class="f-label">Kendaraan *</label>
            <input type="text" name="vehicle_name" value="{{ old('vehicle_name') }}" class="f-input @error('vehicle_name') err @enderror" placeholder="Cth: Toyota Avanza 2020" required>
            @error('vehicle_name')<p class="f-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Jadwal & Layanan</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="f-label">Layanan *</label>
                <select name="service_id" class="f-input f-select @error('service_id') err @enderror" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                    @endforeach
                </select>
                @error('service_id')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">Teknisi</label>
                <input type="text" name="technician_name" value="{{ old('technician_name') }}" class="f-input" placeholder="Nama teknisi">
            </div>
            <div>
                <label class="f-label">Tanggal Booking *</label>
                <input type="date" name="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" class="f-input @error('booking_date') err @enderror" required>
                @error('booking_date')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">Jam *</label>
                <select name="time_slot" class="f-input f-select @error('time_slot') err @enderror" required>
                    @foreach(['08:00:00','09:00:00','10:00:00','11:00:00','13:00:00','14:00:00','15:00:00','16:00:00'] as $t)
                    <option value="{{ $t }}" {{ old('time_slot') === $t ? 'selected' : '' }}>{{ substr($t,0,5) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
    <label class="f-label">Status</label>
    <select name="status" class="f-input f-select">
        @foreach([
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Diproses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ] as $k => $v)

            <option value="{{ $k }}"
                {{ old('status', 'pending') === $k ? 'selected' : '' }}>
                {{ $v }}
            </option>

        @endforeach
    </select>
</div>
            <div>
                <label class="f-label">Estimasi Biaya (Rp)</label>
                <input type="number" name="estimated_cost" value="{{ old('estimated_cost') }}" class="f-input" min="0" placeholder="Otomatis dari harga layanan">
                <p style="font-size:.72rem;color:#94A3B8;margin-top:.2rem;">Kosongkan untuk pakai harga default layanan</p>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <label class="f-label">Catatan</label>
            <textarea name="notes" rows="3" class="f-input" style="resize:vertical;" placeholder="Keluhan pelanggan atau catatan tambahan...">{{ old('notes') }}</textarea>
        </div>
    </div>

    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">✓ Simpan Booking</button>
    </div>
</div>
</form>
</div>
@endsection