{{-- resources/views/services/show.blade.php --}}
@extends('layouts.app')
@section('title', $service->name)

@push('styles')
<style>
.page-hero { background:linear-gradient(135deg,#0F172A 0%,#1E293B 100%); padding:7rem 0 3rem; margin-top:-1px; }
.detail-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.5rem; padding:2rem; }
.related-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.25rem; padding:1.5rem; transition:all .25s; }
.related-card:hover { box-shadow:0 12px 32px rgba(15,23,42,.1); transform:translateY(-3px); border-color:#F97316; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm text-slate-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('services.index') }}" class="hover:text-white transition-colors">Layanan</a>
            <span>/</span>
            <span class="text-white">{{ $service->name }}</span>
        </nav>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-orange-500/20 rounded-2xl flex items-center justify-center text-3xl">{{ $service->icon_emoji }}</div>
            <div>
                @if($service->category)<span class="badge-orange mb-1 inline-block">{{ $service->category }}</span>@endif
                <h1 class="font-display text-3xl sm:text-4xl font-bold text-white">{{ $service->name }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="detail-card">
                <h2 class="font-display font-bold text-xl text-slate-900 mb-3">Deskripsi Layanan</h2>
                <p class="text-slate-600 leading-relaxed">{{ $service->description }}</p>
            </div>

            <div class="detail-card">
                <h2 class="font-display font-bold text-xl text-slate-900 mb-4">Yang Termasuk dalam Layanan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach(['Pemeriksaan awal kendaraan','Penanganan oleh teknisi ahli','Spare part berkualitas','Laporan hasil servis','Garansi pengerjaan','Uji jalan pasca servis'] as $item)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="text-sm text-slate-700">{{ $item }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="detail-card">
                <h2 class="font-display font-bold text-xl text-slate-900 mb-4">Alur Servis</h2>
                <div class="space-y-4">
                    @foreach(['Booking jadwal','Kedatangan & pemeriksaan awal','Pengerjaan oleh teknisi','Quality control','Selesai & pembayaran'] as $i => $step)
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">{{ $i+1 }}</div>
                        <div class="flex-1 h-px bg-slate-200 {{ $i === 4 ? 'opacity-0' : '' }}"></div>
                        <span class="text-sm text-slate-700 font-medium min-w-[120px]">{{ $step }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">
            <div class="detail-card sticky top-20">
                <div class="text-center mb-5">
                    <p class="text-slate-400 text-sm mb-1">Harga Mulai</p>
                    <p class="font-display text-4xl font-bold text-orange-500">{{ $service->formatted_price }}</p>
                    <!-- <p class="text-slate-400 text-sm mt-1">Estimasi waktu: <strong class="text-slate-700">{{ $service->duration_minutes }} menit</strong></p> -->
                </div>

                <div class="space-y-3 mb-5">
                    <div class="flex justify-between text-sm py-2.5 border-b border-slate-100">
                        <span class="text-slate-500">Kategori</span>
                        <span class="font-semibold text-slate-900">{{ $service->category ?? 'Umum' }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-2.5 border-b border-slate-100">
                        <!-- <span class="text-slate-500">Durasi</span>
                        <span class="font-semibold text-slate-900">{{ $service->duration_minutes }} menit</span> -->
                    </div>
                    <div class="flex justify-between text-sm py-2.5">
                        <span class="text-slate-500">Garansi</span>
                        <span class="font-semibold text-green-600">✓ Tersedia</span>
                    </div>
                </div>

                <a href="https://wa.me/628123456789?text=Halo,%20saya%20ingin%20booking%20layanan%20{{ urlencode($service->name) }}" target="_blank" class="btn-orange w-full justify-center py-3 mb-3">
                    📅 Booking Sekarang
                </a>
                <a href="{{ route('services.index') }}" class="btn-outline w-full justify-center py-2.5 text-sm">
                    ← Kembali ke Layanan
                </a>
            </div>
        </div>
    </div>

    {{-- Related Services --}}
    @if($related->count() > 0)
    <div class="mt-14">
        <h2 class="font-display text-2xl font-bold text-slate-900 mb-6">Layanan Serupa</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($related as $rel)
            <a href="{{ route('services.show', $rel->id) }}" class="related-card block">
                <div class="text-2xl mb-3">{{ $rel->icon_emoji }}</div>
                <h3 class="font-semibold text-slate-900 mb-1">{{ $rel->name }}</h3>
                <p class="text-xs text-slate-500 mb-3">{{ Str::limit($rel->description, 70) }}</p>
                <p class="text-orange-500 font-bold text-sm">{{ $rel->formatted_price }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
