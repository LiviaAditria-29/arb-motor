{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<meta name="test-layout" content="APP-LAYOUT-AKTIF-123">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ARB Motor') – Bengkel Terpercaya</title>
    <meta name="description" content="@yield('meta_desc', 'ARB Motor – Bengkel mobil profesional dengan teknisi berpengalaman.')">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php echo '<!-- VITE-AKTIF -->'; @endphp

    <style>
        :root {
            --navy: #0F172A;
            --navy-800: #1E293B;
            --navy-700: #334155;
            --orange: #F97316;
            --orange-hover: #EA6C0A;
            --white: #FFFFFF;
            --gray-50: #F8FAFC;
            --gray-100: #ffa200;
            --gray-400: #94A3B8;
            --gray-600: #475569;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--white); color: var(--navy); }
        .font-display { font-family: 'Space Grotesk', sans-serif; }

        /* NAVBAR */
        .navbar-scroll { background: rgba(15,23,42,0.97); backdrop-filter: blur(12px); }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes countUp { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade-in-up { animation: fadeInUp 0.7s ease both; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        /* TOAST */
        #toast-container { position: fixed; top: 1.5rem; right: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.5rem; }
        .toast { padding: 0.875rem 1.25rem; border-radius: 0.75rem; color: #ffffff; font-size: 0.875rem; font-weight: 500; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 0.5rem; animation: fadeInUp 0.4s ease; }
        .toast-success { background: #16A34A; }
        .toast-error   { background: #DC2626; }
        .toast-info    { background: #2563EB; }

        /* SKELETON */
        .skeleton { background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%); background-size: 200% 100%; animation: skeleton-loading 1.5s infinite; border-radius: 0.5rem; }
        @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* BTN ORANGE */
        .btn-orange { background: var(--orange); color: #fff; font-weight: 600; border-radius: 0.625rem; padding: 0.6rem 1.4rem; transition: background 0.2s, transform 0.15s, box-shadow 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-orange:hover { background: var(--orange-hover); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(249,115,22,0.35); }
        .btn-navy  { background: var(--navy); color: #fff; font-weight: 600; border-radius: 0.625rem; padding: 0.6rem 1.4rem; transition: background 0.2s, transform 0.15s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-navy:hover { background: var(--navy-800); transform: translateY(-1px); }
        .btn-outline { border: 2px solid var(--navy); color: var(--navy); font-weight: 600; border-radius: 0.625rem; padding: 0.55rem 1.35rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-outline:hover { background: var(--navy); color: #fff; }

        /* CARD HOVER */
        .card-hover { transition: transform 0.25s, box-shadow 0.25s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(15,23,42,0.12); }

        /* BADGE */
        .badge-orange { background: #FFF0E6; color: var(--orange); font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.65rem; border-radius: 9999px; }
        .badge-navy   { background: #EFF6FF; color: var(--navy); font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.65rem; border-radius: 9999px; }
    </style>

    @stack('styles')
</head>
<body class="antialiased">

{{-- NAVBAR --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent" x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 40">
    <div :class="scrolled ? 'navbar-scroll shadow-lg' : 'bg-transparent'" class="transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-18">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">ARB</div>
                    <span class="font-display font-bold text-xl text-white tracking-tight">Motor</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-8">
                    @php
                        $navLinks = [
                            ['href' => route('home'),              'label' => 'Beranda',    'name' => 'home'],
                            ['href' => route('services.index'),    'label' => 'Layanan',    'name' => 'services*'],
                            ['href' => route('spare-parts.index'), 'label' => 'Spare Part', 'name' => 'spare-parts*'],
                        ];
                    @endphp
                    @foreach($navLinks as $link)
                        <a href="{{ $link['href'] }}"
                           class="text-sm font-medium transition-colors duration-200 relative group
                                  {{ request()->routeIs($link['name']) ? 'text-orange-400' : 'text-slate-300 hover:text-white' }}">
                            {{ $link['label'] }}
                            <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-orange-400 transition-all duration-300 group-hover:w-full {{ request()->routeIs($link['name']) ? 'w-full' : '' }}"></span>
                        </a>
                    @endforeach
                </div>

                {{-- CTA + Auth --}}
                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-slate-300 hover:text-white transition-colors">Dashboard</a>
                    @endauth
                    <!-- <a href="{{ route('services.index') }}" class="btn-orange text-sm">
                        Booking Sekarang -->
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg> -->
                    <!-- </a> -->
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="open = !open" class="lg:hidden p-2 text-slate-300 hover:text-white">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open"  xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" x-transition class="lg:hidden bg-slate-900 border-t border-slate-700">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}"              class="block text-slate-300 hover:text-white py-2 text-sm font-medium">Beranda</a>
                <a href="{{ route('services.index') }}"    class="block text-slate-300 hover:text-white py-2 text-sm font-medium">Layanan</a>
                <a href="{{ route('spare-parts.index') }}" class="block text-slate-300 hover:text-white py-2 text-sm font-medium">Spare Part</a>
                <!-- <a href="{{ route('services.index') }}" class="btn-orange w-full justify-center mt-2 text-sm">Konsultasi Sekarang</a> -->
            </div>
        </div>
    </div>
</nav>

{{-- TOAST CONTAINER --}}
<div id="toast-container"></div>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('success') }}', 'success'));</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded', () => showToast('{{ session('error') }}', 'error'));</script>
@endif

{{-- MAIN CONTENT --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-slate-900 text-slate-300 pt-16 pb-8 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">ARB</div>
                    <span class="font-display font-bold text-xl text-white">Motor</span>
                </div>
                <p class="text-sm leading-relaxed text-slate-400">Bengkel terpercaya untuk perawatan dan perbaikan kendaraan Anda dengan layanan profesional dan teknisi berpengalaman.</p>
                <div class="flex gap-3 mt-5">
                    <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Layanan --}}
            <div>
                <h6 class="text-white font-semibold text-sm mb-4">Layanan</h6>
                <ul class="space-y-2 text-sm">
                    @foreach(['Ganti Oli','Servis Ringan','Servis Berat','Tune Up','Ganti Kampas Rem'] as $l)
                    <li><a href="{{ route('services.index') }}" class="hover:text-orange-400 transition-colors">{{ $l }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h6 class="text-white font-semibold text-sm mb-4">Kontak</h6>
                <ul class="space-y-3 text-sm">
                    <li class="flex gap-3"><span class="text-orange-400 mt-0.5">📞</span><span>+62 812-3456-7890</span></li>
                    <li class="flex gap-3"><span class="text-orange-400 mt-0.5">✉️</span><span>info@arbmotor.com</span></li>
                    <li class="flex gap-3"><span class="text-orange-400 mt-0.5">📍</span><span>Jl. Sudajaya Hilir, Kota Sukabumi</span></li>
                </ul>
            </div>

            {{-- Jam Operasional --}}
            <div>
                <h6 class="text-white font-semibold text-sm mb-4">Jam Operasional</h6>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span class="text-slate-400">Senin – Sabtu</span><span class="text-white font-medium">08:00 – 17:00</span></li>
                    <li class="flex justify-between"></li>
                    <li class="flex justify-between"><span class="text-slate-400">Minggu</span><span class="text-red-400 font-medium">Tutup</span></li>
                </ul>
            </div>

        </div>

        <div class="border-t border-slate-800 pt-8 text-center">
            <p class="text-sm text-slate-500">
                © {{ date('Y') }} ARB Motor. Semua hak cipta dilindungi.
            </p>
        </div>
    </div>
</footer>

<script>
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const icons = { success: '✓', error: '✕', info: 'ℹ' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<span>${icons[type]}</span><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(100%)'; toast.style.transition = 'all 0.3s'; setTimeout(() => toast.remove(), 300); }, 3500);
}
window.showToast = showToast;
</script>

<script type="module">
    import Chatbot from "https://cdn.jsdelivr.net/npm/flowise-embed/dist/web.js";

    Chatbot.init({
        chatflowid: "397cd170-4ea1-4a0d-8c75-a08c9757f957",
        apiHost: "http://localhost:3000",
    });
</script>

@stack('scripts')
</body>
</html>
