{{-- resources/views/spareparts/show.blade.php --}}
@extends('layouts.app')
@section('title', $sparePart->name)

@push('styles')
<style>
.page-hero { background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%); padding:7rem 0 3rem; margin-top:-1px; }
.detail-img { width:100%; aspect-ratio:1; object-fit:cover; border-radius:1.5rem; background:#F1F5F9; }
.detail-img-placeholder { width:100%; aspect-ratio:1; background:linear-gradient(135deg,#F1F5F9,#E2E8F0); border-radius:1.5rem; display:flex; align-items:center; justify-content:center; font-size:5rem; color:#CBD5E1; }
.info-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.5rem; padding:1.75rem; }
.spec-row { display:flex; justify-content:space-between; align-items:center; padding:.75rem 0; border-bottom:1px solid #F1F5F9; }
.spec-row:last-child { border-bottom:none; }
.related-card { background:#fff; border:1px solid #E2E8F0; border-radius:1rem; overflow:hidden; transition:all .25s; }
.related-card:hover { box-shadow:0 12px 30px rgba(15,23,42,.1); transform:translateY(-3px); border-color:#F97316; }
.related-card img { width:100%; aspect-ratio:4/3; object-fit:cover; }
.related-card-placeholder { width:100%; aspect-ratio:4/3; background:linear-gradient(135deg,#F1F5F9,#E2E8F0); display:flex; align-items:center; justify-content:center; font-size:2rem; color:#CBD5E1; }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-4 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('spare-parts.index') }}" class="hover:text-white transition-colors">Spare Part</a>
            <span>/</span>
            <span class="text-white line-clamp-1">{{ $sparePart->name }}</span>
        </nav>
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-white">{{ $sparePart->name }}</h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

        {{-- Image Column --}}
        <div class="lg:col-span-2">
            <div class="sticky top-20">
                @if($sparePart->image)
                    <img
                        src="{{ Str::startsWith($sparePart->image, ['http://', 'https://'])
                                ? $sparePart->image
                                : asset('storage/'.$sparePart->image) }}"
                        alt="{{ $sparePart->name }}"
                        class="detail-img shadow-lg">
                @else
                    <div class="detail-img-placeholder shadow-lg">⚙️</div>
                @endif

                {{-- Stock indicator --}}
                @php
                    $stockClass = $sparePart->stock === 0 ? 'bg-red-50 border-red-200 text-red-700' : ($sparePart->stock <= 3 ? 'bg-yellow-50 border-yellow-200 text-yellow-700' : 'bg-green-50 border-green-200 text-green-700');
                    $stockLabel = $sparePart->stock === 0 ? '✕ Stok Habis' : ($sparePart->stock <= 3 ? '⚠ Stok Terbatas ('.$sparePart->stock.')' : '✓ Stok Tersedia ('.$sparePart->stock.' '.$sparePart->unit.')');
                @endphp
                <div class="mt-4 p-3 border rounded-xl text-sm font-semibold text-center {{ $stockClass }}">{{ $stockLabel }}</div>
            </div>
        </div>

        {{-- Details Column --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- Price + CTA --}}
            <div class="info-card">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                    <div>
                        @if($sparePart->brand)
                        <p class="text-sm text-slate-400 mb-1">{{ $sparePart->brand }}</p>
                        @endif
                        @if($sparePart->category)
                        <span class="badge-orange">{{ $sparePart->category }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Harga</p>
                        <p class="font-display text-3xl font-bold text-orange-500">{{ $sparePart->formatted_price }}</p>
                        <p class="text-xs text-slate-400">per {{ $sparePart->unit }}</p>
                    </div>
                </div>

                <!-- <div class="flex gap-3">
                    <a href="https://wa.me/628123456789?text=Halo,%20saya%20ingin%20menanyakan%20spare%20part:%20{{ urlencode($sparePart->name) }}"
                       target="_blank"
                       class="btn-orange flex-1 justify-center py-3 {{ $sparePart->stock===0?'opacity-50 pointer-events-none':'' }}">
                        💬 Tanya via WhatsApp
                    </a>
                    <a href="{{ route('spare-parts.index') }}" class="btn-outline px-4 py-3">←</a>
                </div> -->
            </div>

            {{-- Deskripsi --}}
            @if($sparePart->description)
            <div class="info-card">
                <h2 class="font-display font-bold text-lg text-slate-900 mb-3">Deskripsi Produk</h2>
                <p class="text-slate-600 leading-relaxed text-sm">{{ $sparePart->description }}</p>
            </div>
            @endif

            {{-- Spesifikasi --}}
            <div class="info-card">
                <h2 class="font-display font-bold text-lg text-slate-900 mb-2">Spesifikasi</h2>
                <div>
                    @if($sparePart->brand)
                    <div class="spec-row"><span class="text-sm text-slate-500">Merek</span><span class="text-sm font-semibold text-slate-900">{{ $sparePart->brand }}</span></div>
                    @endif
                    @if($sparePart->category)
                    <div class="spec-row"><span class="text-sm text-slate-500">Kategori</span><span class="text-sm font-semibold text-slate-900">{{ $sparePart->category }}</span></div>
                    @endif
                    <div class="spec-row"><span class="text-sm text-slate-500">Satuan</span><span class="text-sm font-semibold text-slate-900">{{ $sparePart->unit }}</span></div>
                    <div class="spec-row"><span class="text-sm text-slate-500">Stok</span><span class="text-sm font-semibold {{ $sparePart->stock===0?'text-red-600':($sparePart->stock<=3?'text-yellow-600':'text-green-600') }}">{{ $sparePart->stock }} {{ $sparePart->unit }}</span></div>
                    @if($sparePart->compatible_vehicles)
                    <div class="spec-row"><span class="text-sm text-slate-500">Kompatibel</span><span class="text-sm font-semibold text-slate-900 text-right max-w-[55%]">{{ $sparePart->compatible_vehicles }}</span></div>
                    @endif
                </div>
            </div>

            {{-- Keamanan Belanja --}}
            <div class="info-card bg-slate-50 border-slate-100">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div><div class="text-2xl mb-1">🛡️</div><p class="text-xs text-slate-600 font-medium">Garansi Produk</p></div>
                    <div><div class="text-2xl mb-1">✅</div><p class="text-xs text-slate-600 font-medium">Part Original</p></div>
                    <div><div class="text-2xl mb-1">🚗</div><p class="text-xs text-slate-600 font-medium">Pasang di Bengkel</p></div>
                </div>
            </div>
        </div>
    </div>

    <!-- {{-- Related Products --}}
    @if($related->count() > 0)
    <div class="mt-16">
        <h2 class="font-display text-2xl font-bold text-slate-900 mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($related as $rel)
            <a href="{{ route('spare-parts.show', $rel->id) }}" class="related-card group">
                @if($rel->image)
                    <img src="{{ asset('storage/'.$rel->image) }}" alt="{{ $rel->name }}" class="group-hover:scale-105 transition-transform duration-300">
                @else
                    <div class="related-card-placeholder">⚙️</div>
                @endif
                <div class="p-3">
                    <p class="text-xs text-slate-500 mb-1">{{ $rel->brand }}</p>
                    <h4 class="text-sm font-semibold text-slate-900 line-clamp-2 mb-1">{{ $rel->name }}</h4>
                    <p class="text-orange-500 font-bold text-sm">{{ $rel->formatted_price }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif -->
</div>
@endsection
