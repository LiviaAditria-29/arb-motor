{{-- resources/views/admin/spare-parts/create.blade.php --}}
@extends('layouts.admin')
@section('title','Tambah Spare Part')
@section('page-title','Tambah Spare Part')
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.spare-parts.index') }}" style="color:#64748B;text-decoration:none;">Spare Part</a> <span>/</span> <span style="color:#0F172A;">Tambah</span> @endsection

@push('styles')
<style>
.upload-zone{border:2px dashed #E2E8F0;border-radius:1rem;padding:2rem;text-align:center;cursor:pointer;transition:all .2s;}
.upload-zone:hover,.upload-zone.drag{border-color:#F97316;background:rgba(249,115,22,.03);}
.img-preview{width:100%;max-height:240px;object-fit:contain;border-radius:.875rem;border:1.5px solid #E2E8F0;}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.spare-parts.store') }}" enctype="multipart/form-data">
@csrf
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;max-width:1000px;">

    {{-- Image Upload --}}
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1rem;font-size:.875rem;">Gambar Produk</p>

            <div id="preview-wrap" style="display:none;margin-bottom:.875rem;position:relative;">
                <img id="img-prev" class="img-preview" src="" alt="Preview">
                <button type="button" onclick="removeImg()" style="position:absolute;top:.5rem;right:.5rem;background:#EF4444;color:#fff;border:none;border-radius:50%;width:24px;height:24px;cursor:pointer;font-size:.75rem;display:flex;align-items:center;justify-content:center;">✕</button>
            </div>

            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('img-inp').click()">
                <div id="upload-ph">
                    <div style="font-size:2.5rem;margin-bottom:.625rem;">🖼️</div>
                    <p style="font-size:.845rem;font-weight:600;color:#475569;">Klik atau drag & drop</p>
                    <p style="font-size:.75rem;color:#94A3B8;margin-top:.25rem;">JPG, PNG, WEBP — maks 2 MB</p>
                </div>
            </div>
            <input type="file" id="img-inp" name="image" accept="image/jpeg,image/png,image/webp" style="display:none;">
            @error('image')<p class="f-error" style="margin-top:.5rem;">{{ $message }}</p>@enderror
            <p style="font-size:.72rem;color:#94A3B8;text-align:center;margin-top:.625rem;">Rasio 1:1 direkomendasikan</p>
        </div>
    </div>

    {{-- Form Fields --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.875rem;">Informasi Produk</p>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <label class="f-label">Nama Spare Part *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="f-input @error('name') err @enderror" placeholder="Cth: Oli Mesin Shell 10W-40" required>
                    @error('name')<p class="f-error">{{ $message }}</p>@enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="f-label">Merek</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" class="f-input" placeholder="Cth: Shell, NGK, Bosch">
                    </div>
                    <div>
                        <label class="f-label">Kategori</label>
                        <input type="text" name="category" value="{{ old('category') }}" class="f-input" list="cat-list" placeholder="Cth: Oli, Filter, Rem">
                        <datalist id="cat-list">
                            @foreach($categories as $c)<option value="{{ $c }}">@endforeach
                            <option value="Oli"><option value="Filter"><option value="Rem">
                            <option value="Busi"><option value="Aki"><option value="Wiper">
                        </datalist>
                    </div>
                </div>
                <div>
                    <label class="f-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="f-input" style="resize:vertical;" placeholder="Deskripsi lengkap produk...">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="f-label">Kendaraan Kompatibel</label>
                    <input type="text" name="compatible_vehicles" value="{{ old('compatible_vehicles') }}" class="f-input" placeholder="Cth: Toyota Avanza, Honda Jazz, Mitsubishi Xpander">
                </div>
            </div>
        </div>

        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.875rem;">Harga & Stok</p>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
                <div>
                    <label class="f-label">Harga (Rp) *</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:.875rem;top:50%;transform:translateY(-50%);font-size:.82rem;color:#94A3B8;">Rp</span>
                        <input type="number" name="price" value="{{ old('price') }}" class="f-input @error('price') err @enderror" style="padding-left:2.5rem;" min="0" required>
                    </div>
                    @error('price')<p class="f-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="f-label">Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock',0) }}" class="f-input @error('stock') err @enderror" min="0" required>
                    @error('stock')<p class="f-error">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="f-label">Satuan *</label>
                    <select name="unit" class="f-input f-select">
                        @foreach($units as $u)
                        <option value="{{ $u }}" {{ old('unit')===$u?'selected':'' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">✓ Simpan Spare Part</button>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const inp=document.getElementById('img-inp');
const prev=document.getElementById('img-prev');
const wrap=document.getElementById('preview-wrap');
const ph=document.getElementById('upload-ph');
const zone=document.getElementById('upload-zone');

inp.addEventListener('change',function(){
    if(this.files[0]){
        if(this.files[0].size>2*1024*1024){showToast('Ukuran gambar maks 2 MB','error');this.value='';return;}
        const r=new FileReader();
        r.onload=e=>{prev.src=e.target.result;wrap.style.display='block';ph.style.display='none';};
        r.readAsDataURL(this.files[0]);
    }
});

function removeImg(){inp.value='';prev.src='';wrap.style.display='none';ph.style.display='block';}

zone.addEventListener('dragover',e=>{e.preventDefault();zone.classList.add('drag');});
zone.addEventListener('dragleave',()=>zone.classList.remove('drag'));
zone.addEventListener('drop',e=>{
    e.preventDefault();zone.classList.remove('drag');
    const f=e.dataTransfer.files[0];
    if(f&&['image/jpeg','image/png','image/webp'].includes(f.type)){
        const dt=new DataTransfer();dt.items.add(f);inp.files=dt.files;inp.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
