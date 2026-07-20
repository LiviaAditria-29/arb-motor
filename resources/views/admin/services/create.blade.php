{{-- resources/views/admin/services/create.blade.php --}}
@extends('layouts.admin')
@section('title','Tambah Layanan')
@section('page-title','Tambah Layanan')
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.services.index') }}" style="color:#64748B;text-decoration:none;">Layanan</a> <span>/</span> <span style="color:#0F172A;">Tambah</span> @endsection

@section('content')
<div style="max-width:700px;">
<form method="POST" action="{{ route('admin.services.store') }}">
@csrf
<div style="display:flex;flex-direction:column;gap:1.25rem;">
    <div class="card">
        <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;">Detail Layanan</p>
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div>
                <label class="f-label">Nama Layanan *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="f-input @error('name') err @enderror" placeholder="Cth: Ganti Oli, Tune Up" required>
                @error('name')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">Kategori *</label>
                <select name="category" class="f-input f-select @error('category') err @enderror" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ old('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="f-label">Deskripsi *</label>
                <textarea name="description" rows="4" class="f-input @error('description') err @enderror" style="resize:vertical;" placeholder="Jelaskan apa yang termasuk dalam layanan ini..." required>{{ old('description') }}</textarea>
                @error('description')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label class="f-label">Harga (Rp) *</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);font-size:.82rem;color:#94A3B8;font-weight:500;">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" class="f-input @error('price') err @enderror" style="padding-left:2.5rem;" min="0" placeholder="50000" required>
                    </div>
                    @error('price')<p class="f-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="f-label">Durasi (menit) *</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes',30) }}" class="f-input @error('duration_minutes') err @enderror" min="1" required>
                    @error('duration_minutes')<p class="f-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:.75rem;justify-content:flex-end;">
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">✓ Simpan Layanan</button>
    </div>
</div>
</form>
</div>
@endsection
