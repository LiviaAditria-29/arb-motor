{{-- resources/views/admin/spareparts/create.blade.php --}}
@extends('layouts.admin')
@section('title', 'Tambah Spare Part')
@section('page-title', 'Tambah Spare Part')
@section('page-subtitle', 'Isi data spare part baru')

@push('styles')
<style>
.form-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.5rem; padding:2rem; }
.form-label { display:block; font-size:.8rem; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.5rem; }
.form-input { width:100%; border:1.5px solid #E2E8F0; border-radius:.75rem; padding:.7rem 1rem; font-size:.9rem; outline:none; transition:border-color .2s,box-shadow .2s; background:#fff; }
.form-input:focus { border-color:#F97316; box-shadow:0 0 0 3px rgba(249,115,22,.1); }
.form-input.error { border-color:#EF4444; }
.form-error { color:#EF4444; font-size:.75rem; margin-top:.3rem; }
.upload-area { border:2px dashed #E2E8F0; border-radius:1rem; padding:2rem; text-align:center; cursor:pointer; transition:all .2s; }
.upload-area:hover,.upload-area.drag { border-color:#F97316; background:rgba(249,115,22,.03); }
.img-preview-wrap { display:none; position:relative; }
.img-preview-wrap.show { display:block; }
.img-preview { width:100%; max-height:260px; object-fit:contain; border-radius:.75rem; border:1.5px solid #E2E8F0; }
.remove-preview { position:absolute; top:.5rem; right:.5rem; background:#EF4444; color:#fff; border:none; border-radius:9999px; width:26px; height:26px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:.9rem; }
</style>
@endpush

@section('content')

<div class="mb-5">
    <a href="{{ route('admin.spare-parts.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar
    </a>
</div>

<form method="POST" action="{{ route('admin.spare-parts.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Left: Image Upload --}}
        <div class="xl:col-span-1">
            <div class="form-card sticky top-20">
                <h2 class="font-display font-bold text-slate-900 mb-5">Gambar Produk</h2>

                {{-- Preview --}}
                <div class="img-preview-wrap mb-3" id="preview-wrap">
                    <img id="img-preview" src="" alt="Preview" class="img-preview">
                    <button type="button" class="remove-preview" onclick="removeImage()" title="Hapus gambar">✕</button>
                </div>

                {{-- Upload Area --}}
                <div class="upload-area" id="upload-area" onclick="document.getElementById('image').click()">
                    <div id="upload-placeholder">
                        <div class="text-4xl mb-3">🖼️</div>
                        <p class="text-slate-600 font-medium text-sm">Klik atau drag & drop gambar</p>
                        <p class="text-slate-400 text-xs mt-1">JPG, PNG, WEBP – maks. 2 MB</p>
                    </div>
                </div>

                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" class="hidden">

                @error('image')
                <p class="form-error mt-2">{{ $message }}</p>
                @enderror

                <p class="text-xs text-slate-400 mt-3 text-center">Gambar direkomendasikan rasio 1:1 (kotak)</p>
            </div>
        </div>

        {{-- Right: Form Fields --}}
        <div class="xl:col-span-2 space-y-5">
            <div class="form-card">
                <h2 class="font-display font-bold text-slate-900 mb-5">Informasi Dasar</h2>
                <div class="space-y-4">

                    <div>
                        <label for="name" class="form-label">Nama Spare Part <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="form-input @error('name') error @enderror" placeholder="Cth: Oli Mesin Shell 10W-40">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="brand" class="form-label">Merek</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand') }}"
                                   class="form-input" placeholder="Cth: Shell, NGK, Bosch">
                        </div>
                        <div>
                            <label for="category" class="form-label">Kategori</label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}"
                                   class="form-input" placeholder="Cth: Oli, Filter, Rem"
                                   list="category-list">
                            <datalist id="category-list">
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                                @endforeach
                                <option value="Oli">
                                <option value="Filter">
                                <option value="Rem">
                                <option value="Busi">
                                <option value="Aki">
                                <option value="Ban">
                                <option value="Kopling">
                                <option value="AC">
                            </datalist>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="form-label">Deskripsi Produk</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-input resize-none" placeholder="Deskripsi lengkap produk...">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="compatible_vehicles" class="form-label">Kendaraan Kompatibel</label>
                        <input type="text" name="compatible_vehicles" id="compatible_vehicles"
                               value="{{ old('compatible_vehicles') }}"
                               class="form-input" placeholder="Cth: Toyota Avanza, Honda Jazz, Mitsubishi Xpander">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2 class="font-display font-bold text-slate-900 mb-5">Harga & Stok</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}"
                                   class="form-input pl-10 @error('price') error @enderror" placeholder="0" min="0">
                        </div>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="stock" class="form-label">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock',0) }}"
                               class="form-input @error('stock') error @enderror" min="0">
                        @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="unit" class="form-label">Satuan <span class="text-red-500">*</span></label>
                        <select name="unit" id="unit" class="form-input">
                            @foreach(['pcs','liter','set','pasang','roll','meter','botol','kaleng'] as $u)
                            <option value="{{ $u }}" {{ old('unit')===$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                        @error('unit')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.spare-parts.index') }}" class="btn-secondary px-6 py-2.5">Batal</a>
                <button type="submit" class="btn-orange px-8 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Spare Part
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
const imageInput = document.getElementById('image');
const previewWrap = document.getElementById('preview-wrap');
const imgPreview  = document.getElementById('img-preview');
const uploadArea  = document.getElementById('upload-area');
const uploadPH    = document.getElementById('upload-placeholder');

imageInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran gambar maksimal 2 MB', 'error');
            this.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            previewWrap.classList.add('show');
            uploadPH.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

function removeImage() {
    imageInput.value = '';
    imgPreview.src = '';
    previewWrap.classList.remove('show');
    uploadPH.style.display = 'block';
}

// Drag & drop
uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag'); });
uploadArea.addEventListener('dragleave', ()=> uploadArea.classList.remove('drag'));
uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('drag');
    const file = e.dataTransfer.files[0];
    if (file && ['image/jpeg','image/png','image/webp'].includes(file.type)) {
        const dt = new DataTransfer();
        dt.items.add(file);
        imageInput.files = dt.files;
        imageInput.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
