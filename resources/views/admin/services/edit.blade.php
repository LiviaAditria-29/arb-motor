{{-- resources/views/admin/services/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Layanan')
@section('page-title','Edit Layanan: '.$service->name)
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.services.index') }}" style="color:#64748B;text-decoration:none;">Layanan</a> <span>/</span> <span style="color:#0F172A;">Edit</span> @endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('admin.services.update',$service->id) }}">
@csrf @method('PUT')
<div style="display:flex;flex-direction:column;gap:1.25rem;">
    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Detail Layanan</p>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div>
                <label class="f-label">Nama Layanan *</label>
                <input type="text" name="name" value="{{ old('name',$service->name) }}" class="f-input" required>
            </div>
            <div>
                <label class="f-label">Kategori *</label>
                <select name="category" class="f-input f-select" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category',$service->category)===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="f-label">Deskripsi *</label>
                <textarea name="description" rows="4" class="f-input" style="resize:vertical;" required>{{ old('description',$service->description) }}</textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="f-label">Harga (Rp) *</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);font-size:.82rem;color:#94A3B8;">Rp</span>
                        <input type="number" name="price" value="{{ old('price',$service->price) }}" class="f-input" style="padding-left:2.5rem;" min="0" required>
                    </div>
                </div>
                <div>
                    <label class="f-label">Durasi (menit) *</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes',$service->duration_minutes) }}" class="f-input" min="1" required>
                </div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
    </div>
</div>
</form>
</div>
@endsection
