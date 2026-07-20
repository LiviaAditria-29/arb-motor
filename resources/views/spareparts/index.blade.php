{{-- resources/views/spareparts/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Spare Part')

@push('styles')
<style>
.page-hero { background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%); padding:7rem 0 3.5rem; margin-top:-1px; }
.part-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.25rem; overflow:hidden; transition:all .3s; display:flex; flex-direction:column; }
.part-card:hover { box-shadow:0 20px 50px rgba(15,23,42,.1); transform:translateY(-4px); border-color:#F97316; }
.part-card-img { width:100%; aspect-ratio:4/3; object-fit:cover; background:#F1F5F9; }
.part-card-img-placeholder { width:100%; aspect-ratio:4/3; background:linear-gradient(135deg,#F1F5F9,#E2E8F0); display:flex; align-items:center; justify-content:center; font-size:2.5rem; color:#94A3B8; }
.stock-badge { display:inline-flex; align-items:center; gap:0.3rem; font-size:.7rem; font-weight:700; padding:.2rem .6rem; border-radius:9999px; }
.stock-in  { background:#DCFCE7; color:#16A34A; }
.stock-low { background:#FEF3C7; color:#D97706; }
.stock-out { background:#FEE2E2; color:#DC2626; }
.select-styled { background:#fff; border:1.5px solid #E2E8F0; border-radius:.75rem; padding:.55rem 2rem .55rem 1rem; font-size:.875rem; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right .75rem center; cursor:pointer; }
.select-styled:focus { border-color:#F97316; outline:none; }
.search-wrap { position:relative; }
.search-wrap input { padding:.7rem 1rem .7rem 2.5rem; border:1.5px solid #E2E8F0; border-radius:.875rem; font-size:.875rem; width:100%; outline:none; transition:border-color .2s; }
.search-wrap input:focus { border-color:#F97316; box-shadow:0 0 0 3px rgba(249,115,22,.1); }
.search-wrap svg { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#94A3B8; pointer-events:none; }
.filter-chip { padding:.35rem .9rem; border-radius:9999px; font-size:.78rem; font-weight:600; border:1.5px solid #E2E8F0; background:#fff; color:#64748B; cursor:pointer; transition:all .2s; }
.filter-chip.active,.filter-chip:hover { background:#0F172A; color:#fff; border-color:#0F172A; }
.skeleton { background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size:200% 100%; animation:skel 1.5s infinite; border-radius:.75rem; }
@keyframes skel { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 bg-orange-500/15 text-orange-400 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Spare Part</span>
        <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-3">Spare Part Berkualitas</h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">Temukan spare part original & berkualitas untuk kendaraan Anda</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('spare-parts.index') }}" id="filter-form">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-8">
            <div class="flex flex-col lg:flex-row gap-4">

                {{-- Search --}}
                <div class="search-wrap flex-1">
                    <svg style="width:17px;height:17px" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" id="search-input" value="{{ $search ?? '' }}" placeholder="Cari spare part atau merek...">
                </div>

                {{-- Sort --}}
                <div>
                    <select name="sort" class="select-styled" onchange="this.form.submit()">
                        <option value="latest"     {{ ($sort??'latest')==='latest'     ? 'selected':'' }}>Terbaru</option>
                        <option value="name"       {{ ($sort??'')==='name'       ? 'selected':'' }}>Nama A-Z</option>
                        <option value="price_asc"  {{ ($sort??'')==='price_asc'  ? 'selected':'' }}>Harga Termurah</option>
                        <option value="price_desc" {{ ($sort??'')==='price_desc' ? 'selected':'' }}>Harga Termahal</option>
                    </select>
                </div>
            </div>

            {{-- Category chips --}}
            @if($categories->count() > 0)
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('spare-parts.index', array_filter(['search'=>$search,'sort'=>$sort])) }}"
                   class="filter-chip {{ empty($category) ? 'active' : '' }}">Semua</a>
                @foreach($categories as $cat)
                <a href="{{ route('spare-parts.index', array_filter(['search'=>$search,'sort'=>$sort,'category'=>$cat])) }}"
                   class="filter-chip {{ $category===$cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
            @endif
        </div>
    </form>

    {{-- Count + reset --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
        <p class="text-sm text-slate-500">
            Menampilkan <strong class="text-slate-900">{{ $spareParts->total() }}</strong> spare part
            @if($search) – pencarian "<em>{{ $search }}</em>" @endif
        </p>
        @if($search || $category)
        <a href="{{ route('spare-parts.index') }}" class="text-sm text-orange-500 hover:underline font-medium">✕ Reset filter</a>
        @endif
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="parts-grid">
        @forelse($spareParts as $part)
        <div class="part-card group">

            {{-- Image --}}
            <a href="{{ route('spare-parts.show', $part->id) }}" class="block overflow-hidden">
                @if($part->image)
                    <img
                        src="{{ Str::startsWith($part->image, ['http://', 'https://']) ? $part->image : asset('storage/'.$part->image) }}"
                        alt="{{ $part->name }}"
                        class="part-card-img group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="part-card-img-placeholder group-hover:bg-slate-100 transition-colors">⚙️</div>
                @endif
            </a>

            {{-- Body --}}
            <div class="p-4 flex flex-col flex-1">

                {{-- Stock badge --}}
                @php
                    $stockClass = $part->stock === 0 ? 'stock-out' : ($part->stock <= 3 ? 'stock-low' : 'stock-in');
                    $stockLabel = $part->stock === 0 ? 'Habis' : ($part->stock <= 3 ? 'Stok Terbatas' : 'Tersedia');
                @endphp
                <div class="flex items-center justify-between mb-2">
                    <span class="stock-badge {{ $stockClass }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $part->stock===0?'bg-red-500':($part->stock<=3?'bg-yellow-500':'bg-green-500') }}"></span>
                        {{ $stockLabel }}
                    </span>
                    @if($part->category)
                    <span class="text-xs text-slate-400">{{ $part->category }}</span>
                    @endif
                </div>

                <h3 class="font-semibold text-slate-900 text-sm leading-snug mb-1 line-clamp-2">{{ $part->name }}</h3>
                @if($part->brand)
                <p class="text-xs text-slate-400 mb-2">{{ $part->brand }}</p>
                @endif

                <div class="mt-auto pt-3 border-t border-slate-100">
                    <p class="font-bold text-orange-500 text-base mb-2">{{ $part->formatted_price }}</p>
                    <p class="text-xs text-slate-400 mb-3">Stok: {{ $part->stock }} {{ $part->unit }}</p>
                    <a href="{{ route('spare-parts.show', $part->id) }}"
                       class="btn-navy w-full justify-center text-xs py-2 {{ $part->stock===0?'opacity-50 pointer-events-none':'' }}">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center py-16">
            <div class="text-6xl mb-4">⚙️</div>
            <h3 class="text-xl font-semibold text-slate-600 mb-2">Spare part tidak ditemukan</h3>
            <p class="text-slate-400 mb-6">Coba kata kunci atau filter yang berbeda</p>
            <a href="{{ route('spare-parts.index') }}" class="btn-navy inline-flex px-6 py-2.5 text-sm">Reset Filter</a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($spareParts->hasPages())
    <div class="mt-10 flex justify-center">
        {{ $spareParts->links('vendor.pagination.tailwind') }}
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
let debounce;
document.getElementById('search-input').addEventListener('input', function(){
    clearTimeout(debounce);
    debounce = setTimeout(()=> document.getElementById('filter-form').submit(), 500);
});
</script>
@endpush
