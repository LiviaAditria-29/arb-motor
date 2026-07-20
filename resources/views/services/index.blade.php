{{-- resources/views/services/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Layanan')

@push('styles')
<style>
.page-hero { background: linear-gradient(135deg,#0F172A 0%,#1E293B 100%); padding: 7rem 0 4rem; margin-top: -1px; }
.service-card { background:#fff; border:1px solid #E2E8F0; border-radius:1.25rem; padding:1.75rem; transition:all .3s; position:relative; overflow:hidden; }
.service-card::after { content:''; position:absolute; bottom:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#F97316,#FBBF24); transform:scaleX(0); transition:transform .3s; }
.service-card:hover { box-shadow:0 20px 50px rgba(15,23,42,.1); transform:translateY(-4px); border-color:#F97316; }
.service-card:hover::after { transform:scaleX(1); }
.icon-wrap { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin-bottom:1.25rem; }
.filter-btn { padding:.4rem 1.1rem; border-radius:9999px; font-size:.8rem; font-weight:600; border:1.5px solid #E2E8F0; color:#64748B; background:#fff; cursor:pointer; transition:all .2s; white-space:nowrap; }
.filter-btn.active, .filter-btn:hover { background:#0F172A; color:#fff; border-color:#0F172A; }
.search-wrap { position:relative; }
.search-wrap input { padding:.75rem 1rem .75rem 2.75rem; border:1.5px solid #E2E8F0; border-radius:.875rem; font-size:.9rem; width:100%; transition:border-color .2s; outline:none; }
.search-wrap input:focus { border-color:#F97316; box-shadow:0 0 0 3px rgba(249,115,22,.1); }
.search-wrap svg { position:absolute; left:.85rem; top:50%; transform:translateY(-50%); color:#94A3B8; }
.empty-state { text-align:center; padding:5rem 1rem; color:#94A3B8; }
.skeleton { background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%); background-size:200% 100%; animation:skel 1.5s infinite; border-radius:.75rem; }
@keyframes skel { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
</style>
@endpush

@section('content')

{{-- Page Hero --}}
<div class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <span class="inline-flex items-center gap-2 bg-orange-500/15 text-orange-400 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">✦ Layanan Kami</span>
            <h1 class="font-display text-4xl sm:text-5xl font-bold text-white mb-3">Layanan Servis Profesional</h1>
            <p class="text-slate-400 text-lg max-w-xl mx-auto">Perawatan mobil berkualitas dengan harga transparan dan teknisi berpengalaman</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-8">
        <form method="GET" action="{{ route('services.index') }}" id="filter-form">
            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">

                {{-- Search --}}
                <div class="search-wrap flex-1">
                    <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari layanan..." id="search-input">
                </div>

                {{-- Category Filter --}}
                <div class="flex flex-wrap gap-2">
                    <button type="submit" name="category" value="" class="filter-btn {{ empty($category) ? 'active' : '' }}">Semua</button>
                    @foreach($categories as $cat)
                    <button type="submit" name="category" value="{{ $cat }}" class="filter-btn {{ $category === $cat ? 'active' : '' }}">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>
        </form>
    </div>

    {{-- Result count --}}
    @if($search || $category)
    <div class="mb-6 flex items-center gap-2 text-sm text-slate-500">
        <span>{{ $services->count() }} layanan ditemukan</span>
        @if($search) <span class="badge-orange">Pencarian: "{{ $search }}"</span> @endif
        @if($category) <span class="badge-navy">{{ $category }}</span> @endif
        <a href="{{ route('services.index') }}" class="ml-auto text-orange-500 hover:underline font-medium text-sm">Reset filter</a>
    </div>
    @endif

    {{-- Services Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="services-grid">
        @forelse($services as $service)
        <div class="service-card">
            {{-- Icon --}}
            <div class="icon-wrap bg-orange-50">{{ $service->icon_emoji }}</div>

            {{-- Category badge --}}
            @if($service->category)
            <span class="badge-orange mb-3 inline-block">{{ $service->category }}</span>
            @endif

            <h3 class="font-display font-bold text-lg text-slate-900 mb-2">{{ $service->name }}</h3>
            <p class="text-slate-500 text-sm leading-relaxed mb-5">{{ $service->description }}</p>

            <!-- {{-- Details --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-400 mb-0.5">Harga</p>
                    <p class="font-bold text-orange-500 text-base">{{ $service->formatted_price }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-400 mb-0.5">Estimasi</p>
                    <p class="font-bold text-slate-800 text-base">{{ $service->duration_minutes }}'</p>
                </div>
            </div> -->

            {{-- Checklist --}}
            <ul class="space-y-1.5 mb-5">
                <li class="flex items-center gap-2 text-xs text-slate-600"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Teknisi berpengalaman</li>
                <li class="flex items-center gap-2 text-xs text-slate-600"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Spare part original</li>
                <li class="flex items-center gap-2 text-xs text-slate-600"><svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Garansi pengerjaan</li>
            </ul>
            <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="text-xs text-slate-400 mb-0.5">Harga</p>
                    <p class="font-bold text-orange-500 text-base">{{ $service->formatted_price }}</p>
                </div>
            <a href="{{ route('services.show', $service->id) }}" class="btn-orange w-full justify-center text-sm py-2.5">
                Lihat Detail & Booking
            </a>
        </div>
        @empty
        <div class="col-span-3 empty-state">
            <div class="text-6xl mb-4">🔧</div>
            <h3 class="text-xl font-semibold text-slate-600 mb-2">Layanan tidak ditemukan</h3>
            <p class="text-slate-400 mb-6">Coba kata kunci lain atau hapus filter yang aktif</p>
            <a href="{{ route('services.index') }}" class="btn-navy inline-flex px-6 py-2.5 text-sm">Reset Filter</a>
        </div>
        @endforelse
    </div>
</div>

{{-- CTA --}}
<div class="bg-slate-900 mx-4 sm:mx-6 lg:mx-8 rounded-3xl py-14 px-6 text-center mb-12">
    <h2 class="font-display text-2xl sm:text-3xl font-bold text-white mb-3">Tidak Menemukan Layanan yang Sesuai?</h2>
    <p class="text-slate-400 mb-6">Hubungi kami langsung untuk konsultasi dan estimasi biaya gratis</p>
    <button id="open-chatbot" class="btn-orange inline-flex px-8 py-3.5 text-base">
    💬 Kami Siap Membantu
    </button>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =============================
    // Live Search
    // =============================
    let debounceTimer;

    const searchInput = document.getElementById('search-input');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);

            debounceTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        });
    }

    // =============================
    // Tombol Buka Chatbot
    // =============================
    const chatBtn = document.getElementById('open-chatbot');

    if (chatBtn) {
        chatBtn.addEventListener('click', function () {

            // cari tombol Flowise yang mengambang
            const flowiseButton = document.querySelector(
                'button[aria-label*="chat"], button[aria-label*="Chat"]'
            );

            if (flowiseButton) {
                flowiseButton.click();
                return;
            }

            // fallback
            const chatbot = document.querySelector('flowise-chatbot');

            if (chatbot && chatbot.shadowRoot) {

                const toggle =
                    chatbot.shadowRoot.querySelector('.chatbot-toggle') ||
                    chatbot.shadowRoot.querySelector('button');

                if (toggle) {
                    toggle.click();
                }
            }

        });
    }

});
</script>
@endpush
