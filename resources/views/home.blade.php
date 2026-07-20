{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<style>
    .hero-bg {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
        position: relative;
        overflow: hidden;
    }
    .hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 80% 60% at 70% 50%, rgba(249,115,22,0.12) 0%, transparent 70%);
    }
    .hero-grid {
        background-image: linear-gradient(rgba(248,250,252,0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(248,250,252,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .stat-card {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s;
    }
    .stat-card:hover { transform: translateY(-3px); }

    .why-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 1.25rem;
        padding: 2rem;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    .why-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #F97316, #FBBF24);
        transform: scaleX(0);
        transition: transform 0.3s;
    }
    .why-card:hover { box-shadow: 0 20px 50px rgba(15,23,42,0.1); border-color: #F97316; }
    .why-card:hover::before { transform: scaleX(1); }

    .testimonial-swiper { overflow: hidden; }
    .swiper-slide { padding: 0.5rem; }

    .section-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #FFF0E6;
        color: #F97316;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        padding: 0.35rem 1rem;
        border-radius: 9999px;
        margin-bottom: 1rem;
    }
    .section-tag.dark {
        background: rgba(249,115,22,0.15);
        color: #FB923C;
    }

    .cta-section {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(249,115,22,0.2) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Counter animation */
    .counter { transition: all 0.3s; }

    /* Scroll reveal */
    .reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════
     HERO SECTION
═══════════════════════════════════════════════ --}}
<section class="hero-bg hero-grid min-h-screen flex items-center pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left Content --}}
            <div class="animate-fade-in-up">
                <div class="section-tag dark">
                    <span class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></span>
                    Bengkel Profesional Sukabumi
                </div>
                <h1 class="font-display text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Servis Mobil
                    <span class="text-orange-400 block">Terpercaya &</span>
                    <span class="text-slate-500">Profesional</span>
                </h1>
                <p class="text-slate-400 text-lg leading-relaxed mb-8 max-w-lg">
                    ARB Motor menghadirkan layanan servis mobil berkualitas dengan teknisi berpengalaman dan spare part original. Kepuasan Anda adalah prioritas kami.
                </p>
                <div class="flex flex-wrap gap-4 mb-10">
                    <button type="button"
                            onclick="openFlowiseChat()"
                            class="btn-orange text-base px-6 py-3">
                        Konsultasi Sekarang
                    </button>
                    <a href="{{ route('spare-parts.index') }}" class="text-blue border border-slate-600 hover:border-orange-400 hover:text-orange-400 font-semibold rounded-xl px-6 py-3 transition-all duration-200 flex items-center gap-2">
                        Lihat Spare Part
                    </a>
                </div>

                {{-- Trust badges --}}
                <div class="flex flex-wrap gap-6 items-center">
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Bergaransi
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Teknisi Berpengalaman
                    </div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Spare Part Original
                    </div>
                </div>
            </div>

            {{-- Right: Stats Grid --}}
            <div class="animate-fade-in-up delay-200">
                <div class="relative">
                    {{-- Main image card --}}
                    <div class="bg-slate-800 rounded-3xl overflow-hidden shadow-2xl aspect-[4/3] relative">
                        <img src="https://images.unsplash.com/photo-1625047509168-a7026f36de04?q=80&w=880&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                             alt="Teknisi ARB Motor" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>

                        {{-- Badge Jam Operasional --}}
                        <div class="absolute bottom-5 left-5 bg-white/95 backdrop-blur-sm rounded-2xl px-4 py-3 shadow-xl">
                            <p class="text-xs text-slate-500 font-medium">Jam Operasional</p>
                            <p class="text-slate-900 font-bold text-sm">Senin–Sabtu, 08:00–17:00</p>
                        
                        </div>
                    </div>

                    
                </div>
            </div>

        </div>

        {{-- Mini Stats Bar --}}
        
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     LAYANAN FEATURED
═══════════════════════════════════════════════ --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <div class="section-tag">✦ Layanan Kami</div>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900">Layanan Perawatan Terlengkap</h2>
            <p class="text-slate-500 mt-3 max-w-xl mx-auto">Dari servis ringan hingga perbaikan besar, semua tersedia dengan harga transparan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($services as $i => $service)
            <div class="why-card card-hover reveal" style="animation-delay: {{ $i * 0.1 }}s">
                <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl mb-5">
                    {{ $service->icon_emoji }}
                </div>
                <h3 class="font-display font-bold text-lg text-slate-900 mb-2">{{ $service->name }}</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-4">{{ Str::limit($service->description, 90) }}</p>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-400">Mulai dari</p>
                        <p class="font-bold text-orange-500 text-lg">{{ $service->formatted_price }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Estimasi</p>
                        <p class="font-semibold text-slate-700 text-sm">{{ $service->duration_minutes }} menit</p>
                    </div>
                </div>
                <a href="{{ route('services.show', $service->id) }}" class="mt-4 block w-full text-center text-sm font-semibold text-orange-500 hover:text-orange-600 transition-colors">
                    Lihat Detail →
                </a>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-slate-400">Belum ada layanan tersedia</div>
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('services.index') }}" class="btn-navy inline-flex px-8 py-3">
                Lihat Semua Layanan
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     WHY CHOOSE US
═══════════════════════════════════════════════ --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            <div class="reveal">
                <div class="section-tag">✦ Keunggulan Kami</div>
                <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900 mb-5">Mengapa Pilih ARB Motor?</h2>
                <p class="text-slate-500 leading-relaxed mb-8">Kami berkomitmen memberikan pengalaman servis terbaik dengan standar kualitas tertinggi.</p>

                <div class="space-y-5">
                    @php
                    $whys = [
                        ['icon' => '🤖', 'title' => 'Konsultasi 24/7',    'desc' => 'Booking dan konsultasi otomatis kapan saja dengan asisten kami'],
                        ['icon' => '🏆', 'title' => 'Teknisi Berpengalaman',   'desc' => 'Mekanik profesional dan berpengalaman'],
                        ['icon' => '⚡', 'title' => 'Pengerjaan Cepat',        'desc' => 'Servis efisien dan tepat waktu, kendaraan Anda tidak lama menunggu'],
                        ['icon' => '🔎', 'title' => 'Harga Transparan',        'desc' => 'Estimasi biaya jelas sebelum pengerjaan, tanpa biaya tersembunyi'],
                    ];
                    @endphp
                    @foreach($whys as $w)
                    <div class="flex gap-4 p-4 rounded-xl hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">{{ $w['icon'] }}</div>
                        <div>
                            <h4 class="font-semibold text-slate-900 mb-1">{{ $w['title'] }}</h4>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $w['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="reveal">
                <div class="relative">
                    <div class="bg-slate-900 rounded-3xl overflow-hidden aspect-square shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1592891024295-ed15966fcba0?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                             alt="Teknisi ARB Motor" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/30 to-orange-900/20"></div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</section>

<!-- {{-- ═══════════════════════════════════════════════
     TESTIMONIALS
═══════════════════════════════════════════════ --}}
@if($testimonials->count() > 0)
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 reveal">
            <div class="section-tag">✦ Testimoni</div>
            <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900">Apa Kata Pelanggan Kami?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="testimonials-grid">
            @foreach($testimonials as $t)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 card-hover reveal">
                <div class="flex items-center gap-1 mb-3 text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $t->rating ? '★' : '☆' }}
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">"{{ $t->comment }}"</p>
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm">
                        {{ strtoupper(substr($t->customer_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $t->customer_name }}</p>
                        @if($t->vehicle)<p class="text-xs text-slate-400">{{ $t->vehicle }}</p>@endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif -->

{{-- ═══════════════════════════════════════════════
     CTA SECTION (before footer)
═══════════════════════════════════════════════ --}}
<section class="cta-section py-20 mx-4 sm:mx-6 lg:mx-8 rounded-3xl my-12">
    <div class="max-w-4xl mx-auto px-6 text-center relative">
        <div class="section-tag dark mx-auto w-fit mb-6">✦ Siap Mulai?</div>
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-white mb-4">
            Jadwalkan Servis Mobil<br>Anda Sekarang
        </h2>
        <p class="text-slate-400 text-lg mb-8 max-w-xl mx-auto">
            Dapatkan estimasi biaya instan dan booking jadwal servis dengan mudah. Teknisi kami siap membantu!
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <button type="button"
                    onclick="openFlowiseChat()"
                    class="btn-orange text-base px-8 py-3.5">
                Konsultasi Sekarang
            </button>
            <a href="{{ route('spare-parts.index') }}" class="text-white border border-slate-600 hover:border-white font-semibold rounded-xl px-8 py-3.5 transition-all duration-200 flex items-center gap-2">
                Cek Spare Part
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Counter animation
function animateCounters() {
    document.querySelectorAll('.counter').forEach(el => {
        const target = parseInt(el.dataset.target) || 0;
        if (target === 0) { el.textContent = '0'; return; }
        let current = 0;
        const step = Math.max(1, Math.floor(target / 60));
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString('id-ID');
            if (current >= target) clearInterval(timer);
        }, 25);
    });
}

// Scroll reveal
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.15 });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// Trigger counters when stats section is visible
const statsObserver = new IntersectionObserver((entries) => {
    if (entries[0].isIntersecting) { animateCounters(); statsObserver.disconnect(); }
}, { threshold: 0.3 });

const firstStat = document.querySelector('.counter');
if (firstStat) statsObserver.observe(firstStat.closest('section') || firstStat);
</script>
@endpush

<script type="module">
import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js"

Chatbot.init({
    chatflowid: "397cd170-4ea1-4a0d-8c75-a08c9757f957",
    apiHost: "http://localhost:3000",
    theme: {
        button: {
            backgroundColor: '#F97316', // warna tombol bubble (orange sesuai tema ARB)
            iconColor: '#ffffff',
        },
        chatWindow: {
            backgroundColor: '#ffffff',
            headerTitle: 'ARB Motor Assistant',
            headerBackgroundColor: '#0F172A', // navy sesuai tema
            headerFontColor: '#ffffff',
            botMessageBackground: '#F1F5F9',
            botMessageTextColor: '#1E293B',
            userMessageBackground: '#F97316', // orange
            userMessageTextColor: '#ffffff',
            sendButtonColor: '#F97316',
            fontSize: 14,
        }
    }
})
</script>

<script>
function openFlowiseChat() {
    const flowise = document.querySelector('flowise-chatbot') || document.querySelector('flowise-fullchatbot');
    
    if (flowise && flowise.shadowRoot) {
        const shadowBtn = flowise.shadowRoot.querySelector('button');
        if (shadowBtn) {
            shadowBtn.click();
            return;
        }
    }
    console.warn('Flowise chatbot belum siap.');
}
</script>

