{{-- resources/views/admin/spare-parts/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Spare Part')
@section('page-title','Edit: '.Str::limit($sparePart->name,40))
@section('breadcrumb') <span>/</span> <a href="{{ route('admin.spare-parts.index') }}" style="color:#64748B;text-decoration:none;">Spare Part</a> <span>/</span> <span style="color:#0F172A;">Edit</span> @endsection

@push('styles')
<style>
.upload-zone{border:2px dashed #E2E8F0;border-radius:1rem;padding:1.5rem;text-align:center;cursor:pointer;transition:all .2s;}
.upload-zone:hover,.upload-zone.drag{border-color:#F97316;background:rgba(249,115,22,.03);}
.img-preview{width:100%;max-height:220px;object-fit:contain;border-radius:.875rem;border:1.5px solid #E2E8F0;}
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.spare-parts.update',$sparePart->id) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div style="display:grid;grid-template-columns:300px 1fr;gap:1.25rem;max-width:1000px;">

    {{-- Image --}}
    <div>
        <div class="card" style="position:sticky;top:5rem;">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1rem;font-size:.875rem;">Gambar Produk</p>

            <div style="margin-bottom:.875rem;">
                <img id="img-prev" class="img-preview"
                     src="{{ $sparePart->image_url }}"
                     alt="{{ $sparePart->name }}"
                     style="{{ !$sparePart->image ? 'display:none;' : '' }}">
                <div id="img-ph" style="{{ $sparePart->image ? 'display:none;' : '' }}background:#F8FAFC;border-radius:.875rem;height:140px;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#CBD5E1;">⚙️</div>
            </div>

            <div class="upload-zone" id="upload-zone" onclick="document.getElementById('img-inp').click()">
                <div style="font-size:1.75rem;margin-bottom:.5rem;">📤</div>
                <p style="font-size:.82rem;font-weight:600;color:#475569;">Klik untuk ganti gambar</p>
                <p style="font-size:.72rem;color:#94A3B8;margin-top:.25rem;">JPG, PNG, WEBP — maks 2 MB</p>
            </div>
            <input type="file" id="img-inp" name="image">
            <!-- <input type="file" id="img-inp" name="image" accept="image/jpeg,image/png,image/webp" style="display:none;"> -->
            @error('image')<p class="f-error" style="margin-top:.5rem;">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Form --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">
        <div class="card">
            <p style="font-family:'Space Grotesk',sans-serif;font-weight:700;color:#0F172A;margin-bottom:1.25rem;font-size:.875rem;">Informasi Produk</p>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div>
                    <label class="f-label">Nama Spare Part *</label>
                    <input type="text" name="name" value="{{ old('name',$sparePart->name) }}" class="f-input" required>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                    <div>
                        <label class="f-label">Merek</label>
                        <input type="text" name="brand" value="{{ old('brand',$sparePart->brand) }}" class="f-input">
                    </div>
                    <div>
                        <label class="f-label">Kategori</label>
                        <input type="text" name="category" value="{{ old('category',$sparePart->category) }}" class="f-input" list="cat-list">
                        <datalist id="cat-list">
                            @foreach($categories as $c)<option value="{{ $c }}">@endforeach
                        </datalist>
                    </div>
                </div>
                <div>
                    <label class="f-label">Deskripsi</label>
                    <textarea name="description" rows="3" class="f-input" style="resize:vertical;">{{ old('description',$sparePart->description) }}</textarea>
                </div>
                <div>
                    <label class="f-label">Kendaraan Kompatibel</label>
                    <input type="text" name="compatible_vehicles" value="{{ old('compatible_vehicles',$sparePart->compatible_vehicles) }}" class="f-input">
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
                        <input type="number" name="price" value="{{ old('price',$sparePart->price) }}" class="f-input" style="padding-left:2.5rem;" min="0" required>
                    </div>
                </div>
                <div>
                    <label class="f-label">Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock',$sparePart->stock) }}" class="f-input" min="0" required>
                </div>
                <div>
                    <label class="f-label">Satuan *</label>
                    <select name="unit" class="f-input f-select">
                        @foreach($units as $u)
                        <option value="{{ $u }}" {{ old('unit',$sparePart->unit)===$u?'selected':'' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:.75rem;justify-content:flex-end;">
            <a href="{{ route('admin.spare-parts.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">✓ Simpan Perubahan</button>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const inp=document.getElementById('img-inp');
const prev=document.getElementById('img-prev');
const ph=document.getElementById('img-ph');
const zone=document.getElementById('upload-zone');
inp.addEventListener('change',function(){
    if(this.files[0]){
        if(this.files[0].size>2*1024*1024){showToast('Ukuran gambar maks 2 MB','error');this.value='';return;}
        const r=new FileReader();
        r.onload=e=>{prev.src=e.target.result;prev.style.display='block';ph.style.display='none';};
        r.readAsDataURL(this.files[0]);
    }
});
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
