{{-- resources/views/admin/spareparts/edit.blade.php --}}
@extends('layouts.admin')
@section('title', 'Edit Spare Part')
@section('page-title', 'Edit Spare Part')
@section('page-subtitle', $sparePart->name)

@push('styles')
<style>
.form-card{background:#fff;border:1px solid #E2E8F0;border-radius:1.5rem;padding:2rem;}
.form-label{display:block;font-size:.8rem;font-weight:600;color:#475569;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem;}
.form-input{width:100%;border:1.5px solid #E2E8F0;border-radius:.75rem;padding:.7rem 1rem;font-size:.9rem;outline:none;transition:border-color .2s,box-shadow .2s;background:#fff;}
.form-input:focus{border-color:#F97316;box-shadow:0 0 0 3px rgba(249,115,22,.1);}
.form-input.error{border-color:#EF4444;}
.form-error{color:#EF4444;font-size:.75rem;margin-top:.3rem;}
.upload-area{border:2px dashed #E2E8F0;border-radius:1rem;padding:1.5rem;text-align:center;cursor:pointer;transition:all .2s;}
.upload-area:hover,.upload-area.drag{border-color:#F97316;background:rgba(249,115,22,.03);}
.current-img{width:100%;max-height:220px;object-fit:contain;border-radius:.75rem;border:1.5px solid #E2E8F0;margin-bottom:.75rem;}
.current-img-ph{width:100%;height:160px;background:linear-gradient(135deg,#F1F5F9,#E2E8F0);border-radius:.75rem;display:flex;align-items:center;justify-content:center;font-size:3rem;margin-bottom:.75rem;}
</style>
@endpush

@section('content')
<div class="mb-5">
    <a href="{{ route('admin.spare-parts.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar
    </a>
</div>

<form method="POST" action="{{ route('admin.spare-parts.update', $sparePart->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Image Upload --}}
        <div class="xl:col-span-1">
            <div class="form-card sticky top-20">
                <h2 class="font-display font-bold text-slate-900 mb-5">Gambar Produk</h2>

                {{-- Current/Preview image --}}
                <div id="preview-wrap">
                    @if($sparePart->image)
                        <img id="img-preview" src="{{ asset('storage/'.$sparePart->image) }}" alt="{{ $sparePart->name }}" class="current-img">
                    @else
                        <div id="img-placeholder" class="current-img-ph">⚙️</div>
                        <img id="img-preview" src="" alt="" class="current-img hidden">
                    @endif
                </div>

                <div class="upload-area" id="upload-area" onclick="document.getElementById('image').click()">
                    <div class="text-3xl mb-2">📤</div>
                    <p class="text-slate-600 font-medium text-sm">Klik untuk ganti gambar</p>
                    <p class="text-slate-400 text-xs mt-1">JPG, PNG, WEBP – maks. 2 MB</p>
                </div>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" class="hidden">

                @if($sparePart->image)
                <p class="text-xs text-slate-400 mt-2 text-center">Gambar baru akan menggantikan yang lama</p>
                @endif

                @error('image')<p class="form-error mt-2">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Form Fields --}}
        <div class="xl:col-span-2 space-y-5">
            <div class="form-card">
                <h2 class="font-display font-bold text-slate-900 mb-5">Informasi Dasar</h2>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Nama Spare Part <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $sparePart->name) }}"
                               class="form-input @error('name') error @enderror">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Merek</label>
                            <input type="text" name="brand" value="{{ old('brand', $sparePart->brand) }}" class="form-input" placeholder="Cth: Shell, NGK">
                        </div>
                        <div>
                            <label class="form-label">Kategori</label>
                            <input type="text" name="category" value="{{ old('category', $sparePart->category) }}"
                                   class="form-input" list="category-list">
                            <datalist id="category-list">
                                @foreach($categories as $cat)<option value="{{ $cat }}">@endforeach
                                <option value="Oli"><option value="Filter"><option value="Rem">
                                <option value="Busi"><option value="Aki"><option value="Ban">
                            </datalist>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea name="description" rows="4" class="form-input resize-none">{{ old('description', $sparePart->description) }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Kendaraan Kompatibel</label>
                        <input type="text" name="compatible_vehicles"
                               value="{{ old('compatible_vehicles', $sparePart->compatible_vehicles) }}"
                               class="form-input" placeholder="Cth: Toyota Avanza, Honda Jazz">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2 class="font-display font-bold text-slate-900 mb-5">Harga & Stok</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Harga (Rp) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">Rp</span>
                            <input type="number" name="price" value="{{ old('price', $sparePart->price) }}"
                                   class="form-input pl-10 @error('price') error @enderror" min="0">
                        </div>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $sparePart->stock) }}"
                               class="form-input @error('stock') error @enderror" min="0">
                        @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="form-label">Satuan <span class="text-red-500">*</span></label>
                        <select name="unit" class="form-input">
                            @foreach(['pcs','liter','set','pasang','roll','meter','botol','kaleng'] as $u)
                            <option value="{{ $u }}" {{ old('unit',$sparePart->unit)===$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 justify-end">
                <a href="{{ route('admin.spare-parts.index') }}" class="btn-secondary px-6 py-2.5">Batal</a>
                <button type="submit" class="btn-orange px-8 py-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
const imageInput = document.getElementById('image');
const imgPreview = document.getElementById('img-preview');
const imgPlaceholder = document.getElementById('img-placeholder');
const uploadArea = document.getElementById('upload-area');

imageInput.addEventListener('change', function(){
    if (this.files && this.files[0]) {
        const file = this.files[0];
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran gambar maksimal 2 MB', 'error');
            this.value=''; return;
        }
        const reader = new FileReader();
        reader.onload = e => {
            imgPreview.src = e.target.result;
            imgPreview.classList.remove('hidden');
            if(imgPlaceholder) imgPlaceholder.style.display='none';
        };
        reader.readAsDataURL(file);
    }
});

uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('drag'); });
uploadArea.addEventListener('dragleave', ()=> uploadArea.classList.remove('drag'));
uploadArea.addEventListener('drop', e => {
    e.preventDefault(); uploadArea.classList.remove('drag');
    const file = e.dataTransfer.files[0];
    if (file && ['image/jpeg','image/png','image/webp'].includes(file.type)) {
        const dt = new DataTransfer(); dt.items.add(file);
        imageInput.files = dt.files;
        imageInput.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
